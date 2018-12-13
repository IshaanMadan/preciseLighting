<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\Adminhtml\Customer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magenest\QuickBooksDesktop\Model\Mapping;
use Magento\Customer\Model\CustomerFactory;
use Magenest\QuickBooksDesktop\Model\Config\Source\Status;

/**
 * Class Update
 *
 * @package Magenest\QuickBooksDesktop\Observer\Customer
 */
class Update implements ObserverInterface
{
    /**
     * @var QueueFactory
     */
    protected $_queueFactory;

    /**
     * @var CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * Update constructor.
     * @param QueueFactory $queueFactory
     * @param CustomerFactory $customerFactory
     * @param Mapping $map
     */
    public function __construct(
        QueueFactory $queueFactory,
        CustomerFactory $customerFactory,
        Mapping $map
    ) {
        $this->_customerFactory = $customerFactory;
        $this->_queueFactory = $queueFactory;
        $this->_map = $map;
    }

    /**
     * Admin edit information
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        /** @var \Magento\Framework\Event\Observer $customer */
        $customer = $event->getCustomer();
        $customerId = $customer->getId();
        $customerModel = $this->_customerFactory->create();
        $customerModel->load($customerId);

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $qbId = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
            ->addFieldToFilter('type', 1)->addFieldToFilter('entity_id', $customerId)->getFirstItem()->getData();

        $action = $qbId ? 'Mod' : 'Add';
        $operation = $qbId ? 1 : 2;
        $data = [
            'action_name' => 'Customer' . $action,
            'type' => 'Customer',
            'enqueue_datetime' => time(),
            'entity_id' => $customerId,
            'operation' => $operation,
            'status' => Status::STATUS_QUEUE,
            'company_id' => $companyId
        ];

        $queueCollection = $this->_queueFactory->create()->getCollection();
        /** @var \Magenest\QuickBooksDesktop\Model\Queue $queue */
        $queue = $queueCollection->addFieldToFilter('entity_id', $customerId)
                    ->addFieldToFilter('action_name', 'Customer'.$action)
                    ->addFieldToFilter('company_id', $companyId)
                    ->addFieldToFilter('type', 'Customer')
                    ->addFieldToFilter('status', Status::STATUS_QUEUE)
                    ->getFirstItem();
        $queue->addData($data);
        $queue->save();
    }
}
