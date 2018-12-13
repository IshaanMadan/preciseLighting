<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magento\Sales\Model\Order as OrderModel;
use Magenest\QuickBooksDesktop\Model\QBXML;
use Magenest\QuickBooksDesktop\Model\Mapping;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class SalesOrder extends QBXML
{
    /**
     * @var OrderModel
     */
    protected $_order;

    /**
     * @var Mapping
     */
    public $_map;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * SalesOrder constructor.
     * @param OrderModel $order
     */
    public function __construct(
        OrderModel $order,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Mapping $map
    ) {
        $this->_order = $order;
        $this->scopeConfig = $scopeConfig;
        $this->_map = $map;
    }

    /**
     * Get XML using sync to QBD
     *
     * @param $id
     * @return string
     */
    public function getXml($id)
    {
        $model = $this->_order->load($id);
        $billAddress = $model->getBillingAddress();
        $shipAddress = $model->getShippingAddress();
        $config = $this->scopeConfig->getValue('qbdesktop/qbd_setting/option', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $customerReceive = $model->getCustomerName();
        if ($config == 2) {
            $customerReceive = $this->scopeConfig->getValue('qbdesktop/qbd_setting/customer_receive', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }
        $xml = '<CustomerRef><FullName>' . $customerReceive . '</FullName></CustomerRef>';
        $xml .= '<RefNumber>' . $model->getIncrementId() . '</RefNumber>';
        $xml .= $billAddress ? $this->getAddress($billAddress) : '';
        $xml .= $shipAddress ? $this->getAddress($shipAddress, 'ship') : '';
        $shipMethod = strtok($model->getShippingMethod(), '_');

        if (!empty($model->getShippingMethod())) {
            $xml .= '<ShipMethodRef>
                        <FullName>'.$shipMethod.'</FullName>
                </ShipMethodRef>';
        }

        $i = 1;
        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($model->getAllItems() as $item) {
            $xml .= $this->getOrderItem($item, $i);
            $i++;
        }

        if (!empty($model->getShippingMethod())) {
            $xml .= '<InvoiceLineAdd>';
            $xml .= '<ItemRef>
                        <FullName>'.'Shipping'.'</FullName>
                </ItemRef>';
            $xml .= '<Desc>'.'Method:  '.$model->getShippingMethod().'</Desc>';
            $xml .= '<Quantity>'.''.'</Quantity>';
            $xml .= '<Rate>'.$model->getShippingAmount().'</Rate>';
            $xml .= '</InvoiceLineAdd>';
        }

        return $xml;
    }

    /**
     * Get Address XML
     *
     * @param \Magento\Sales\Model\Order\Address $address
     * @param string $type
     * @return string
     */
    protected function getAddress($address, $type = 'bill')
    {
        if (!$address) {
            return '';
        }

        $xml = $type == 'bill' ? '<BillAddress>' : '<ShipAddress>';
        $xml .= '<Addr1>'.$address->getStreetLine(1).'</Addr1>';
        $xml .= '<Addr2>'.$address->getStreetLine(2).'</Addr2>';
        $xml .= '<City>'.$address->getCity().'</City>';
        $xml .= '<State>'.$address->getRegion().'</State>';
        $xml .= '<PostalCode>'.$address->getPostcode().'</PostalCode>';
        $xml .= '<Country>'.$address->getCountryId().'</Country>';
        $xml .= $type == 'bill' ? '</BillAddress>' : '</ShipAddress>';

        return $xml;
    }

    /**
     * Get Order Item XML
     *
     * @param \Magento\Sales\Model\Order\Item $item     *
     * @return string
     */
    protected function getOrderItem($item)
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');
        $qty = 0;
        $productType = $item->getProductType();
        if ($productType == 'configurable') {
            foreach ($item->getChildrenItems() as $params) {
                $qty     = $params->getQtyOrdered();
            }
        } else {
            $qty     = $item->getQtyOrdered();
        }

        $rateID = $this->prepareTaxCodeRef($item->getItemId(), $companyId);

        if ($item->getPrice() && is_null($item->getParentItemId())) {
            $xml = '<InvoiceLineAdd>';
            $xml .= '<ItemRef>
                        <FullName>'.$item->getName().'</FullName>
                </ItemRef>';
            $xml .= '<Quantity>'.$qty.'</Quantity>';
            $xml .= '<Rate>'.$item->getPrice().'</Rate>';
            if (!empty($rateID)) {
                $xml .= '<SalesTaxCodeRef>';
                $xml .= '<ListID>'.$rateID.'</ListID>';
                $xml .= '</SalesTaxCodeRef>';
            } else {
                $xml .= '<SalesTaxCodeRef>';
                $xml .= '<FullName>'.'E'.'</FullName>';
                $xml .= '</SalesTaxCodeRef>';
            }
            $xml .= '</InvoiceLineAdd>';

            return $xml;
        }

        return '';
    }

    /**
     * Create Tax
     */
    public function prepareTaxCodeRef($itemId, $companyId)
    {
        $modelTaxItem = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Sales\Model\Order\Tax\Item')->load($itemId, 'item_id');
        $rateID = null;
        if ($modelTaxItem) {
            $taxId = $modelTaxItem->getTaxId();
            $modelTax = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Sales\Model\Order\Tax')->load($taxId);

            if ($modelTax && !empty($modelTax->getData())) {
                $taxCode = $modelTax->getCode();
                $modelTaxRate = \Magento\Framework\App\ObjectManager::getInstance()->create('Magento\Tax\Model\Calculation\Rate')->load($taxCode, 'code');
                $taxRateId = $modelTaxRate->getId();
                $mappingTaxRate = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Mapping')->getCollection()->addFieldToFilter('entity_id', $taxRateId)->addFieldToFilter('type', 5)
                    ->addFieldToFilter('company_id', $companyId);
                $rateID = $mappingTaxRate->getFirstItem()->getData('list_id');
            }
        }

        return $rateID;
    }
}
