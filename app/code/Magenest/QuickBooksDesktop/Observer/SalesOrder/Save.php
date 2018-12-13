<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\SalesOrder;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface as ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Save order on frontend
 * @package Magenest\QuickBooksDesktop\Observer\SalesOrder
 */
class Save implements ObserverInterface
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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Save constructor.
     * @param ManagerInterface $messageManager
     * @param ScopeConfigInterface $scopeConfig
     * @param QueueFactory $queueFactory
     */
    public function __construct(
        ManagerInterface $messageManager,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $loggerInterface,
        QueueFactory $queueFactory
    ) {
        $this->logger = $loggerInterface;
        $this->messageManager = $messageManager;
        $this->_scopeConfig   = $scopeConfig;
        $this->_queueFactory  = $queueFactory;
    }

    /**
     * event place order
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getId();
        $model = $this->_queueFactory->create();
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        if ($order->getStatus() == 'pending') {
            $info = [
                'action_name' => 'InvoiceAdd',
                'enqueue_datetime' => time(),
                'type' => 'Invoice',
                'status' => '1',
                'entity_id' => $orderId,
                'operation' => '2',
                'company_id' => $companyId
            ];
            $model->addData($info);
            $model->save();
        }
    }
}
