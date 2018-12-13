<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue;

use Magenest\QuickBooksDesktop\Model\Mapping;
use Magento\Backend\App\Action;
use Magenest\QuickBooksDesktop\Model\QueueFactory;

/**
 * Class SyncPayment
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\Queue
 */
class SyncPayment extends Action
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
     * SyncPayment constructor.
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
     *
     */
    public function execute()
    {
        $totals = 0;
        $paymentMethodsList = $this->_scopeConfig->getValue('payment');
        foreach ($paymentMethodsList as $code => $data) {
            if (isset($data['title'])) {
                $title = $data['title'];
                if (strlen($title) > 31) {
                    $title = substr($title, 0, 31);
                }
            }
            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            $companyId = $company->getData('company_id');
            $check = $this->_map->getCollection()->addFieldToFilter('company_id', $companyId)->addFieldToFilter('type', '7')->addFieldToFilter('payment', $title)->getFirstItem()->getData('list_id');

            if (empty($check)) {
                $info = [
                    'action_name' => 'PaymentMethodAdd',
                    'enqueue_datetime' => time(),
                    'dequeue_datetime' => '',
                    'type' => 'PaymentMethod',
                    'status' => '1',
                    'payment' => $code,
                    'operation' => '2',
                    'company_id' => $companyId,
                ];
                $model = $this->_queueFactory->create();
                $modelCheck = $model->getCollection()->addFieldToFilter('type', 'PaymentMethod')
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
                sprintf('Totals %s Payment Methods Queue have been created/updated', $totals)
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
