<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Controller\Adminhtml\QWC;

use Magenest\QuickBooksDesktop\Model\Mapping;

/**
 * Class Vendor
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\QWC
 */
class Vendor extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var Mapping
     */
    public $_map;

    /**
     * @var \Magenest\QuickBooksDesktop\Model\Connector
     */
    protected $config;

    /**
     * @var \Magenest\QuickBooksDesktop\Model\QueueFactory
     */
    protected $queue;

    /**
     * Vendor constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magenest\QuickBooksDesktop\Model\Connector $config
     * @param Mapping $map
     * @param \Magenest\QuickBooksDesktop\Model\QueueFactory $queueFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magenest\QuickBooksDesktop\Model\Connector $config,
        Mapping $map,
        \Magenest\QuickBooksDesktop\Model\QueueFactory $queueFactory
    ) {
    
        parent::__construct($context);
        $this->config = $config;
        $this->fileFactory = $fileFactory;
        $this->_map = $map;
        $this->queue = $queueFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function execute()
    {
        try {
            $vendor = $this->config->getVendor();
            /** @var \Magenest\QuickBooksDesktop\Model\Company $company */
            $company = \Magento\Framework\App\ObjectManager::getInstance()->create('Magenest\QuickBooksDesktop\Model\Company');
            $company->load(1, 'status');
            if ($company && $company->getId()) {
                $companyId = $company->getData('company_id');
                if (isset($vendor) && !empty($vendor)) {
                    $model = $this->queue->create();
                    $check = $model->load('Vendor', 'type');
                    $info = [
                        'action_name' => 'VendorAdd',
                        'enqueue_datetime' => time(),
                        'type' => 'Vendor',
                        'status' => '1',
                        'operation' => '2',
                        'company_id' => $companyId,
                        'payment' => $vendor
                    ];
                    if ($check && $check->getStatus() == 1) {
                        $check->addData($info)->save();
                    } else {
                        $model->addData($info)->save();
                    }
                }
                $this->messageManager->addSuccessMessage(__('Vendor Sales Tax Agency have added to queue'));
            } else {
                $this->messageManager->addErrorMessage(__('This company haven\'t synchronization'));
            }
        } catch (\Magento\Framework\Exception\LocalizedException  $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->_redirect('adminhtml/system_config/edit/section/qbdesktop');
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
