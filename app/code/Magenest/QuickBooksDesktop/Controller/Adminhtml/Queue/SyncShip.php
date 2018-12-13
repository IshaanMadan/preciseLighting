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
 * Class SyncShip
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue
 */
class SyncShip extends Action
{
    protected $_shippingConfig;

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
     * SyncShip constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param QueueFactory $queueFactory
     * @param Mapping $map
     * @param \Magento\Shipping\Model\Config $shippingConfig
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        QueueFactory $queueFactory,
        Mapping $map,
        \Magento\Shipping\Model\Config $shippingConfig
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $scopeConfig;
        $this->_queueFactory = $queueFactory;
        $this->_map = $map;
        $this->_shippingConfig=$shippingConfig;
    }

    public function execute()
    {
        /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
        $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
        $company->load(1, 'status');
        $companyId = $company->getData('company_id');
        $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '9')->getFirstItem()->getData('list_id');

        if (empty($check)) {
            $info = [
                'action_name' => 'ItemOtherChargeAdd',
                'enqueue_datetime' => time(),
                'dequeue_datetime' => '',
                'type' => 'ItemOtherCharge',
                'status' => '1',
                'operation' => '2',
            ];
            $model = $this->_queueFactory->create();
            $modelCheck = $model->getCollection()->addFieldToFilter('type', 'ItemOtherCharge')
                ->addFieldToFilter('status', 1)->getFirstItem();
            if ($modelCheck) {
                $modelCheck->addData($info);
                $modelCheck->save();
            } else {
                $model->addData($info);
                $model->save();
            }
        }

        $totals = 0;
        $shippingMethodList = $this->_shippingConfig->getAllCarriers();
        foreach ($shippingMethodList as $code => $data) {
            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '9')->addFieldToFilter('payment', $data['id'])->getFirstItem()->getData('list_id');

            if (empty($check) && ( $data['id'] != 'ups' || $data['id'] != 'dhl')) {
                $info = [
                    'action_name' => 'ShipMethodAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'ShipMethod',
                    'status' => '1',
                    'payment' => $data['id'],
                    'company_id' => $companyId,
                    'operation' => '2',
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'ShipMethod')
                    ->addFieldToFilter('payment', $code)->addFieldToFilter('company_id', $companyId)->addFieldToFilter('status', 1)->getFirstItem();
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
                sprintf('Totals %s Shipping Methods Queue have been created/updated', $totals)
            )
        );
        $this->_redirect('*/*/index');
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
