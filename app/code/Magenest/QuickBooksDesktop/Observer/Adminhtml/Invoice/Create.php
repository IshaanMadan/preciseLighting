<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\Adminhtml\Invoice;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface as ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class Create
 * @package Magenest\QuickBooksDesktop\Observer\Invoice
 */
class Create implements ObserverInterface
{
    /**
     * @var QueueFactory
     */
    protected $_queueFactory;

    /**
     * Core Config Data
     *
     * @var $_scopeConfig \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var
     */
    protected $logger;
    
    /**
     * Create constructor.
     * @param ManagerInterface $messageManager
     * @param ScopeConfigInterface $scopeConfig
     * @param QueueFactory $queueFactory
     */
    public function __construct(
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig,
        QueueFactory $queueFactory
    ) {
        $this->messageManager = $messageManager;
        $this->_scopeConfig   = $scopeConfig;
        $this->_queueFactory  = $queueFactory;
    }

    /**
     * Invoice created
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $invoiceId = $invoice->getId();
        $model = $this->_queueFactory->create();

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $info = [
            'action_name' => 'ReceivePaymentAdd',
            'enqueue_datetime' =>time(),
            'type' =>'ReceivePayment',
            'status' => '1',
            'entity_id' => $invoiceId,
            'operation' => '2',
            'company_id' =>$companyId
        ];
        $model->getCollection()->addFieldToFilter('action_name', 'ReceivePaymentAdd')
            ->addFieldToFilter('entity_id', $invoiceId)->addFieldToFilter('company_id', $companyId);
        $model->addData($info);
        $model->save();
    }
}
