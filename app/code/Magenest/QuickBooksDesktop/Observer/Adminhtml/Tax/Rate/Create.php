<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\Adminhtml\Tax\Rate;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magenest\QuickBooksDesktop\Model\Mapping;
use Magenest\QuickBooksDesktop\Model\Company;
use Magento\Tax\Model\Calculation\RateFactory;
use Magenest\QuickBooksDesktop\Model\Config\Source\Status;

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
     * @var RateFactory
     */
    protected $_rateFactory;

    /**
     * @var Mapping
     */
    protected $_map;

    /**
     * @var Company
     */
    protected $_company;

    /**
     * Update constructor.
     * @param QueueFactory $queueFactory
     * @param RateFactory $rateFactory
     * @param Mapping $map
     * @param Company $company
     */
    public function __construct(
        QueueFactory $queueFactory,
        RateFactory $rateFactory,
        Mapping $map,
        Company $company
    ) {
    
        $this->_queueFactory = $queueFactory;
        $this->_rateFactory = $rateFactory;
        $this->_map = $map;
        $this->_company = $company;
    }

    /**
     * Invoice created
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $taxRateId = $observer->getEvent()->getObject()->getData('tax_calculation_rate_id');
        if ($taxRateId) {
            $companyModel = $this->_company->getCollection()->addFieldToFilter('status', '1');
            $companyId = $companyModel->getFirstItem()->getData('company_id');

            $qbId = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
                ->addFieldToFilter('type', 6)->addFieldToFilter('entity_id', $taxRateId)->getFirstItem()->getData();

            $action = $this->getActionName($qbId);
            $operation = $qbId ? 1 : 2;

            $data = [
                'action_name' => 'ItemSalesTax' . $action,
                'type' => 'ItemSalesTax',
                'enqueue_datetime' => time(),
                'entity_id' => $taxRateId,
                'operation' => $operation,
                'status' => Status::STATUS_QUEUE,
                'company_id' => $companyId
            ];

            $queueCollection = $this->_queueFactory->create()->getCollection();
            /** @var \Magenest\QuickBooksDesktop\Model\Queue $queue */
            $queue = $queueCollection->addFieldToFilter('entity_id', $taxRateId)
                ->addFieldToFilter('action_name', 'ItemSalesTax' . $action)
                ->addFieldToFilter('type', 'ItemSalesTax')
                ->addFieldToFilter('company_id', $companyId)
                ->addFieldToFilter('status', Status::STATUS_QUEUE)
                ->getFirstItem();
            $queue->addData($data);
            $queue->save();

            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('entity_id', $taxRateId)->getFirstItem()->getData();
            if (empty($check)) {
                $action = 'Add';
                $operation = 2;

                $data = [
                    'action_name' => 'SalesTaxCode' . $action,
                    'type' => 'SalesTaxCode',
                    'enqueue_datetime' => time(),
                    'entity_id' => $taxRateId,
                    'operation' => $operation,
                    'status' => Status::STATUS_QUEUE,
                    'company_id' => $companyId
                ];

                $queueCollection = $this->_queueFactory->create()->getCollection();
                /** @var \Magenest\QuickBooksDesktop\Model\Queue $queue */
                $queue = $queueCollection->addFieldToFilter('entity_id', $taxRateId)
                    ->addFieldToFilter('action_name', 'SalesTaxCode' . $action)
                    ->addFieldToFilter('company_id', $companyId)
                    ->addFieldToFilter('type', 'SalesTaxCode')
                    ->addFieldToFilter('status', Status::STATUS_QUEUE)
                    ->getFirstItem();
                $queue->addData($data);
                $queue->save();
            }
        }
    }

    /**
     * Get Action
     * @param $qbId
     * @return string
     */
    protected function getActionName($qbId)
    {
        if ($qbId) {
            $action = 'Mod';
        } else {
            $action = 'Add';
        }

        return $action;
    }
}
