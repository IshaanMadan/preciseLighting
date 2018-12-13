<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Controller\Attachment;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Yosto\OrderAttachment\Helper\Data;
use Yosto\OrderAttachment\Helper\FileUploader;
use Magento\Framework\Data\Form\FormKey\Validator;
use \Magento\Checkout\Model\Session;

/**
 * Class Upload
 * @package Yosto\OrderAttachment\Controller\Attachment
 */
class Upload extends \Magento\Framework\App\Action\Action
{
    protected $_fileUploader;

    protected $_formKeyValidator;

    protected $_checkoutSession;

    protected $_helper;

    /**
     * Upload constructor.
     * @param Context $context
     * @param FileUploader $fileUploader
     * @param Validator $formKeyValidator
     * @param Session $checkoutSession
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        FileUploader $fileUploader,
        Validator $formKeyValidator,
        Session $checkoutSession,
        Data $helper
    ) {

        parent::__construct($context);
        $this->_fileUploader = $fileUploader;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;

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
                    $quoteId = $this->_checkoutSession->getQuoteId();
                    $file = $this->_fileUploader->uploadFile($pathToUpload, $quoteId, 'yosto_order_attachment');
                    if ($file) {
                        $this->_checkoutSession->setYostoOrderAttachment("{$pathToUpload}/" . $quoteId . "/" . $file);
                        $this->getResponse()->representJson(
                            json_encode([
                                "success" => 1
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