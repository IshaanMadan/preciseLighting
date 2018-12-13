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
class SyncOrder extends Action
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
    protected $oderCollection;

    /**
     * SyncOrder constructor.
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
            ->addFieldToFilter('type', '3')->getColumnValues('entity_id');
        $orderCollection1 = $this->getCollection()->getAllIds();
        $orderCollection2 = array_diff($orderCollection1, $mappingCollection);

        $orderCollection = $this->getCollection()->addFieldToFilter('entity_id', [
            'in' => $orderCollection2]);

        $totals = 0;

        foreach ($orderCollection as $order) {
            $id = $order->getId();

            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '3')->addFieldToFilter('entity_id', $id)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => 'InvoiceAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'Invoice',
                    'status' => '1',
                    'company_id' => $companyId,
                    'entity_id' => $id,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'Invoice')
                    ->addFieldToFilter('entity_id', $id)->addFieldToFilter('company_id', $companyId)->addFieldToFilter('status', 1)->getFirstItem();
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
                sprintf('Totals %s Order Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
    }

    /**
     * Order Collection
     *
     * @return \Magento\Sales\Model\ResourceModel\Order\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCollection()
    {
        if (!$this->oderCollection) {
            $this->oderCollection = $this->_objectManager
                ->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
        }

        return $this->oderCollection;
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
