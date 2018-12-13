<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue;

use Magenest\QuickBooksDesktop\Model\Mapping;
use Magento\Backend\App\Action;
use Magenest\QuickBooksDesktop\Model\QueueFactory;

/**
 * Class Payment
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue\Sync
 */
class SyncProduct extends Action
{
    /**
     * @var QueueFactory
     */
    protected $_queueFactory;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $_scopeConfig;

    /**
     * @var Mapping
     */
    public $_map;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $productCollection;

    /**
     * SyncProduct constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param QueueFactory $queueFactory
     * @param Mapping $map
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        QueueFactory $queueFactory,
        Mapping $map
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_queueFactory = $queueFactory;
        $this->_map = $map;
    }

    public function execute()
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $mappingCollection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
            ->addFieldToFilter('type', '2')->getColumnValues('entity_id');
        $productCollection1 = $this->getCollection()->getAllIds();
        $productCollection2 = array_diff($productCollection1, $mappingCollection);

        $productCollection = $this->getCollection()->addFieldToFilter('entity_id', [
            'in' => $productCollection2]);

        $totals = 0;

        foreach ($productCollection as $product) {
            /** @var \Magento\Catalog\Model\Product  $productModel */
            $productModel = $this->_objectManager->create('\Magento\Catalog\Model\Product');
            $productId = $product->getId();
            $productModel = $productModel->load($productId);
            $qty = $productModel->getExtensionAttributes()->getStockItem()->getQty();
            $type = $productModel->getTypeId();
            if ($qty > 0 || $type == 'virtual' || $type == 'giftcard' || $type == 'downloadable') {
                $action = 'ItemInventoryAdd';
            } else {
                $action = 'ItemNonInventoryAdd';
            }

            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '2')->addFieldToFilter('entity_id', $productId)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => $action,
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'Product',
                    'status' => '1',
                    'entity_id' => $productId,
                    'company_id' => $companyId,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'Product')
                    ->addFieldToFilter('entity_id', $productId)->addFieldToFilter('company_id', $companyId)->addFieldToFilter('status', 1)->getFirstItem();
                if ($modelCheck) {
                    $modelCheck->addData($info);
                    $modelCheck->save();
                } else {
                    $model->addData($info);
                    $model->save();
                }
                $totals++;
            }
        }
        $this->messageManager->addSuccessMessage(
            __(
                sprintf('Totals %s Product Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection|mixed
     */
    public function getCollection()
    {
        if (!$this->productCollection) {
            $this->productCollection = $this->_objectManager
                ->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        }

        return $this->productCollection;
    }

    /**
     * Always true
     *
     * @return bool
     */
    public function _isAllowed()
    {
        return true;
    }
}
