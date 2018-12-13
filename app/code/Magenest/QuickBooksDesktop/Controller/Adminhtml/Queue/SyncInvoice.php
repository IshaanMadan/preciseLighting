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
 * Class SyncInvoice
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue
 */
class SyncInvoice extends Action
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
    protected $invoiceCollection;

    /**
     * SyncInvoice constructor.
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
            ->addFieldToFilter('type', '4')->getColumnValues('entity_id');
        $invoiceCollection1 = $this->getCollection()->getAllIds();
        $invoiceCollection2 = array_diff($invoiceCollection1, $mappingCollection);

        $invoiceCollection = $this->getCollection()->addFieldToFilter('entity_id', [
            'in' => $invoiceCollection2]);

        $totals = 0;

        foreach ($invoiceCollection as $invoice) {
            $id = $invoice->getId();

            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '4')->addFieldToFilter('entity_id', $id)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => 'ReceivePaymentAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'ReceivePayment',
                    'status' => '1',
                    'company_id' => $companyId,
                    'entity_id' => $id,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'ReceivePayment')
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
                sprintf('Totals %s Invoice Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
    }

    /**
     * invoiceCollection Collection
     *
     * @return \Magento\Sales\Model\ResourceModel\Order\Invoice\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCollection()
    {
        if (!$this->invoiceCollection) {
            $this->invoiceCollection = $this->_objectManager
                ->create('\Magento\Sales\Model\ResourceModel\Order\Invoice\Collection');
        }

        return $this->invoiceCollection;
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
