<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\QBXML;

use Magento\Sales\Model\Order\Invoice as InvoiceModel;
use Magenest\QuickBooksDesktop\Model\QBXML;
use Magenest\QuickBooksDesktop\Model\Connector;
use Magenest\QuickBooksDesktop\Model\Mapping;

/**
 * Class Customer
 *
 * @package Magenest\QuickBooksDesktop\Model\QBXML
 */
class Invoice extends QBXML
{
    /**
     * @var InvoiceModel
     */
    protected $_invoice;

    /**
     * @var Mapping
     */
    public $_map;

    /**
     * @var Connector
     */
    protected $connector;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * Invoice constructor.
     * @param InvoiceModel $invoice
     * @param Connector $connector
     */
    public function __construct(
        InvoiceModel $invoice,
        Connector $connector,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Mapping $map
    ) {
        $this->connector = $connector;
        $this->_invoice = $invoice;
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
        $model = $this->_invoice->load($id);
        $config = $this->connector->getOption();
        $customerName = $model->getOrder()->getCustomerName();
        $customerGr = $model->getOrder()->getCustomerGroupId();
        if ($config == 2 && $customerGr != 2) {
            $customerName = $this->connector->getCustomerReceive();
        }
        $xml = '<CustomerRef><FullName>'.$customerName.'</FullName></CustomerRef>';
        $xml .= '<RefNumber>'.$model->getIncrementId().'</RefNumber>';

        $paymentMethod = $model->getOrder()->getPayment()->getMethodInstance()->getCode();

        $paymentMethodsList = $this->scopeConfig->getValue('payment');
        foreach ($paymentMethodsList as $code => $data) {
            if ($paymentMethod == $code) {
                if (isset($data['title'])) {
                    $title = $data['title'];
                    if (strlen($title) > 31) {
                        $title = substr($title, 0, 31);
                    }
                }
            }
        }
        $xml .= '<TotalAmount>' . str_replace(',', '', number_format($this->_invoice->getBaseGrandTotal(), 2)) . '</TotalAmount>';

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');
        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '7')->addFieldToFilter('payment', $title)->getFirstItem()->getData('list_id');
        if (!empty($check)) {
            $xml .= '<PaymentMethodRef>';
            $xml .= '<ListID>' . $check . '</ListID>';
            $xml .= '</PaymentMethodRef>';
        }

        $orderId = $this->_invoice->getOrderId();
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');
        $txnid = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '3')->addFieldToFilter('entity_id', $orderId)->getFirstItem()->getData('list_id');

        if (!empty($txnid)) {
            $xml .= '<AppliedToTxnAdd>';
            $xml .= '<TxnID useMacro="MACROTYPE">' . $txnid . '</TxnID>';
            $xml .= '<PaymentAmount>' . str_replace(',', '', number_format($this->_invoice->getBaseGrandTotal(), 2)) . '</PaymentAmount>';
            $xml .= '</AppliedToTxnAdd>';
        }
        \Magento\Framework\App\ObjectManager::getInstance()->create('Psr\Log\LoggerInterface')->debug(print_r($xml, true));
        return $xml;
    }
}
