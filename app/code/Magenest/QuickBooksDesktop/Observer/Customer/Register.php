<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\Customer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;

/**
 * Class Register
 *
 * @package Magenest\QuickBooksDesktop\Observer\Customer
 */
class Register implements ObserverInterface
{
    /**
     * @var QueueFactory
     */
    protected $_queueFactory;

    /**
     * Register constructor.
     * @param QueueFactory $queueFactory
     */
    public function __construct(
        QueueFactory $queueFactory
    ) {
        $this->_queueFactory = $queueFactory;
    }

    /**
     * Cutomer add account
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $model = $this->_queueFactory->create();
        $event = $observer->getEvent();
        $customer = $event->getCustomer();
        $customerId = $customer->getId();
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $info = [
            'action_name' => 'CustomerAdd',
            'enqueue_datetime' =>time(),
            'dequeue_datetime' =>'',
            'type' =>'Customer',
            'status' => '1',
            'entity_id' => $customerId,
            'operation' => '2',
            'company_id' => $companyId,
        ];
        $model->load($customerId, 'entity_id');
        $model->addData($info);
        $model->save();
    }
}
