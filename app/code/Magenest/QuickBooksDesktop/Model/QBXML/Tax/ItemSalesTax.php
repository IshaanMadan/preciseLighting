<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML\Tax;

use Magento\Tax\Model\Calculation\Rate as RateModel;
use Magenest\QuickBooksDesktop\Model\Mapping;
use Magenest\QuickBooksDesktop\Model\QBXML;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfigInterface;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class ItemSalesTax extends QBXML
{
    /**
     * @var RateModel
     */
    protected $_rate;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * Item constructor.
     *
     * @param RateModel $rate
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        RateModel $rate,
        ScopeConfigInterface $scopeConfig,
        Mapping $map
    ) {
        $this->_rate = $rate;
        $this->_scopeConfig = $scopeConfig;
        $this->_map = $map;
    }

    /**
     * Get XML using sync to QBD
     * @param $id
     * @return string
     */
    public function getXml($id)
    {
        $model = $this->_rate->load($id);
        $taxRate = $model->getRate();
        $vendorName = $this->_scopeConfig->getValue('qbdesktop/qbd_setting/vendor', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');
        $itemSalesTax = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('entity_id', $id)->getFirstItem()->getData('list_id');

        if (empty($itemSalesTax)) {
            $xml = '<Name>' . $model->getCode() . '</Name>';
            $xml .= '<IsActive>' . 'true' . '</IsActive>';
            $xml .= '<TaxRate>' . $taxRate . '</TaxRate>';
            $xml .= '<TaxVendorRef>';
            $xml .= '<FullName>' . $vendorName . '</FullName>';
            $xml .= '</TaxVendorRef>';
            $xml .= '<SalesTaxReturnLineRef>';
            $xml .= '<ListID>' . '50' . '</ListID>';
            $xml .= '</SalesTaxReturnLineRef>';
        } else {
            $xml = '<Name>' . $model->getCode() . '</Name>';
            $xml .= '<IsActive>' . 'true' . '</IsActive>';
            $xml .= '<TaxRate>' . $taxRate . '</TaxRate>';
            $xml .= '<TaxVendorRef>';
            $xml .= '<FullName>' . $vendorName . '</FullName>';
            $xml .= '</TaxVendorRef>';
        }

        return $xml;
    }
}
