<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Model;


use Magento\Checkout\Model\ConfigProviderInterface;
use Yosto\OrderAttachment\Helper\Data;

/**
 * Class OrderAttachmentConfigProvider
 * @package Yosto\OrderAttachment\Model
 */
class OrderAttachmentConfigProvider implements ConfigProviderInterface
{
    protected $_helper;

    function __construct(Data $helper)
    {
        $this->_helper = $helper;
    }

    public function getConfig()
    {
        $enable = $this->_helper->getEnable();
        $title = $this->_helper->getTitle();
        $isRequire = $this->_helper->getIsRequired();
        $fileExtensions = $this->_helper->getFileExtensions();
        $fileSize = $this->_helper->getLimitedFileSize();
        $allowCustomerEdit = $this->_helper->getAllowCustomerEdit();

        $config = [
          "yosto_order_attachment" => [
              "enable" => $enable,
              "title" => "{$title}",
              "is_required" => $isRequire,
              "file_extensions" => "{$fileExtensions}",
              "file_size" => $fileSize,
              "allow_customer_edit" => $allowCustomerEdit,
              "file_upload_status" => 0
          ]
        ];
        return $config;

    }

}