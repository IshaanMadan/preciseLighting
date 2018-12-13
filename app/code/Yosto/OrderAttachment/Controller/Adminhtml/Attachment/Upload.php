<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Controller\Adminhtml\Attachment;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Yosto\OrderAttachment\Helper\Data;
use Yosto\OrderAttachment\Helper\FileUploader;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Sales\Model\OrderFactory;

/**
 * Class Upload
 * @package Yosto\OrderAttachment\Controller\Adminhtml\Attachment
 */
class Upload extends Action
{

    protected $_fileUploader;

    protected $_formKeyValidator;

    protected $_coreRegistry;

    protected $_orderFactory;

    protected $_helper;

    /**
     * Upload constructor.
     * @param FileUploader $fileUploader
     * @param Validator $formKeyValidator
     * @param Registry $coreRegistry
     * @param OrderFactory $orderFactory
     * @param Data $helper
     * @param Action\Context $context
     */
    public function __construct(
        FileUploader $fileUploader,
        Validator $formKeyValidator,
        Registry $coreRegistry,
        OrderFactory $orderFactory,
        Data $helper,
        Action\Context $context
    ) {
        $this->_fileUploader = $fileUploader;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_coreRegistry = $coreRegistry;
        $this->_orderFactory = $orderFactory;
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {

            try {

                if (!$this->_formKeyValidator->validate($this->getRequest())) {
                    $this->getResponse()->representJson(
                        json_encode([
                            "success" => 0
                        ])
                    );
                } else {
                    $pathToUpload = $this->_helper->getPathToUpload();
                    $quoteId = $this->getRequest()->getParam('yosto_attachment_quote_id');
                    $orderId = $this->getRequest()->getParam('yosto_attachment_order_id');
                    $file = $this->_fileUploader->uploadFile($pathToUpload, $quoteId, 'yosto_order_attachment');
                    if ($file) {
                        $path = "{$pathToUpload}/" . $quoteId . "/" . $file;
                        $order = $this->_orderFactory->create()->load($orderId);
                        $order->setData('yosto_order_attachment', $path);
                        $order->save();

                        $this->getResponse()->representJson(
                            json_encode([
                                "success" => 1,
                                "path" => "{$path}"
                            ])
                        );
                    } else {
                        $this->getResponse()->representJson(
                            json_encode([
                                "success" => 0
                            ])
                        );
                    }
                }

            } catch (\Exception $e) {
                $this->getResponse()->representJson(
                    json_encode([
                        "success" => 0
                    ])
                );
            }
        }
    }

}