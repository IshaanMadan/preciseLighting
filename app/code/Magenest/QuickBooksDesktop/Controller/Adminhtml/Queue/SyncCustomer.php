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
class SyncCustomer extends Action
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
    protected $customerCollection;

    /**
     * SyncCustomer constructor.
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

    /**
     * sync customer to queue table
     */
    public function execute()
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $mappingCollection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
            ->addFieldToFilter('type', '1')->getColumnValues('entity_id');

        $customerCollection1 = $this->getCollection()->getAllIds();
        $customerCollection2 = array_diff($customerCollection1, $mappingCollection);
        $customerCollection = $this->getCollection()->addFieldToFilter('entity_id', [
            'in' => $customerCollection2]);

        $totals = 0;

        foreach ($customerCollection as $customer) {
            $id = $customer->getId();

            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '1')->addFieldToFilter('entity_id', $id)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => 'CustomerAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'Customer',
                    'status' => '1',
                    'entity_id' => $id,
                    'company_id' => $companyId,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'Customer')
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
                sprintf('Totals %s Customer Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
    }

    /**
     * Customer Collection
     *
     * @return \Magento\Customer\Model\ResourceModel\Customer\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCollection()
    {
        if (!$this->customerCollection) {
            $this->customerCollection = $this->_objectManager
                ->create('\Magento\Customer\Model\ResourceModel\Customer\Collection');
        }

        return $this->customerCollection;
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
