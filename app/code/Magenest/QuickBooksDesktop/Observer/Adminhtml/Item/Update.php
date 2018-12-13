<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Observer\Adminhtml\Item;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magenest\QuickBooksDesktop\Model\QueueFactory;
use Magenest\QuickBooksDesktop\Model\Mapping;
use Magenest\QuickBooksDesktop\Model\Company;
use Magento\Catalog\Model\ProductFactory;
use Magenest\QuickBooksDesktop\Model\Config\Source\Status;

/**
 * Class Update
 *
 * @package Magenest\QuickBooksDesktop\Observer\Item
 */
class Update implements ObserverInterface
{
    /**
     * @var QueueFactory
     */
    protected $_queueFactory;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;

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
     * @param ProductFactory $productFactory
     * @param Mapping $map
     * @param Company $company
     */
    public function __construct(
        QueueFactory $queueFactory,
        ProductFactory $productFactory,
        Mapping $map,
        Company $company
    ) {
        $this->_queueFactory = $queueFactory;
        $this->_productFactory = $productFactory;
        $this->_map = $map;
        $this->_company = $company;
    }

    /**
     * Admin save a Product
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $productId = $observer->getEvent()->getProduct()->getId();
        if ($productId) {
            $productModel = $this->_productFactory->create();
            $checkType = $productModel->load($productId);
            $productType = $observer->getEvent()->getProduct()->getTypeId();
            $companyModel = $this->_company->getCollection()->addFieldToFilter('status', '1');
            $companyId = $companyModel->getFirstItem()->getData('company_id');

            $qbId = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
                ->addFieldToFilter('type', 2)->addFieldToFilter('entity_id', $productId)->getFirstItem()->getData();

            $qty = $productModel->getExtensionAttributes()->getStockItem()->getQty();
            $operation = $qbId ? 1 : 2;
            $action = $this->getActionName($qty, $qbId, $productType);
            $testType = ['configurable', 'bundle', 'grouped'];
            if (!in_array($productType, $testType) || (in_array($productType, $testType) && $action == 'ItemNonInventoryAdd')) {
                $data = [
                'action_name' => $action,
                'type' => 'Product',
                'enqueue_datetime' => time(),
                'entity_id' => $productId,
                'operation' => $operation,
                'status' => Status::STATUS_QUEUE,
                'company_id' => $companyId
                ];
                $queueCollection = $this->_queueFactory->create()->getCollection();
                /** @var \Magenest\QuickBooksDesktop\Model\Queue $queue */
                $queue = $queueCollection->addFieldToFilter('entity_id', $productId)
                        ->addFieldToFilter('action_name', $action)
                        ->addFieldToFilter('company_id', $companyId)
                        ->addFieldToFilter('type', 'Product')
                        ->addFieldToFilter('status', Status::STATUS_QUEUE)
                        ->getFirstItem();
                $queue->addData($data);
                $queue->save();
            }
        }
    }

    /**
     * Get Action and Item Type
     *
     * @param $qty
     * @param $qbId
     * @return string
     */
    protected function getActionName($qty, $qbId, $productType)
    {
        if ($qty || $productType == 'virtual' || $productType == 'giftcard') {
            $itemType = 'ItemInventory';
        } else {
            $itemType = 'ItemNonInventory';
        }
        if ($qbId) {
            $action = 'Mod';
        } else {
            $action = 'Add';
        }

        return $itemType.$action;
    }
}
