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
class SyncTax extends Action
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
    protected $taxRateModel;

    /**
     * SyncTax constructor.
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
        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '10')->getFirstItem()->getData('list_id');

        if (empty($check)) {
            $info = [
                'action_name' => 'VendorAdd',
                'enqueue_datetime' => time(),
                'dequeue_datetime' => '',
                'type' => 'Vendor',
                'status' => '1',
                'operation' => '2',
            ];
            $model = $this->_queueFactory->create();
            $modelCheck = $model->getCollection()->addFieldToFilter('type', 'Vendor')->addFieldToFilter('status', 1)->getFirstItem();
            if ($modelCheck) {
                $modelCheck->addData($info);
                $modelCheck->save();
            } else {
                $model->addData($info);
                $model->save();
            }
        }

        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');

        $mappingCollection = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)
            ->addFieldToFilter('type', '6')->getColumnValues('entity_id');
        $taxRateCollection1 = $this->getCollection()->getAllIds();
        $taxRateCollection2 = array_diff($taxRateCollection1, $mappingCollection);

        $taxRateCollection = $this->getCollection()->addFieldToFilter('tax_calculation_rate_id', [
            'in' => $taxRateCollection2]);

        $totals = 0;

        foreach ($taxRateCollection as $taxRate) {
            $id = $taxRate->getData('tax_calculation_rate_id');

            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '6')->addFieldToFilter('entity_id', $id)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => 'ItemSalesTaxAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'ItemSalesTax',
                    'status' => '1',
                    'company_id' => $companyId,
                    'entity_id' => $id,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'ItemSalesTax')
                    ->addFieldToFilter('entity_id', $id)->addFieldToFilter('company_id', $companyId)->addFieldToFilter('status', 1)->getFirstItem();
                if ($modelCheck) {
                    $modelCheck->addData($info);
                    $modelCheck->save();
                } else {
                    $model->addData($info);
                    $model->save();
                }

                $data = [
                    'action_name' => 'SalesTaxCodeAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'SalesTaxCode',
                    'status' => '1',
                    'company_id' => $companyId,
                    'entity_id' => $id,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'SalesTaxCode')
                    ->addFieldToFilter('entity_id', $id)->addFieldToFilter('company_id', $companyId)->addFieldToFilter('status', 1)->getFirstItem();
                if ($modelCheck) {
                    $modelCheck->addData($data);
                    $modelCheck->save();
                } else {
                    $model->addData($data);
                    $model->save();
                }
                $totals++;
            }
        }
        $this->messageManager->addSuccessMessage(
            __(
                sprintf('Totals %s Item Sales Tax Queue have been created/updated', $totals),
                sprintf('Totals %s Sales Tax Code Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getCollection()
    {
        if (!$this->taxRateModel) {
            $this->taxRateModel = $this->_objectManager
                ->create('\Magenest\QuickBooksDesktop\Model\Calculation\Rate')->getCollection();
        }

        return $this->taxRateModel;
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
