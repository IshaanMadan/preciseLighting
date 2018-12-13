<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Data
 * @package Yosto\OrderAttachment\Helper
 */
class Data extends AbstractHelper
{
    const ENABLE = "yosto_order_attachment/general/enable";
    const TITLE = "yosto_order_attachment/general/title";
    const IS_REQUIRED = "yosto_order_attachment/general/is_required";
    const FILE_EXTENSIONS = "yosto_order_attachment/general/file_extensions";
    const ALLOW_CUSTOMER_EDIT = "yosto_order_attachment/general/allow_customer_edit";
    const PATH_TO_UPLOAD = "yosto_order_attachment/general/path_to_upload";
    const FILE_SIZE = "yosto_order_attachment/general/file_size";

    public function getConfigData($path, $storeId = null)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getEnable()
    {
        return $this->getConfigData(self::ENABLE);
    }

    public function getTitle()
    {
        return $this->getConfigData(self::TITLE);
    }

    public function getIsRequired()
    {
        return $this->getConfigData(self::IS_REQUIRED);
    }

    public function getFileExtensions()
    {
        return $this->getConfigData(self::FILE_EXTENSIONS);
    }

    public function getAllowCustomerEdit()
    {
        return $this->getConfigData(self::ALLOW_CUSTOMER_EDIT);
    }

    public function getPathToUpload()
    {
        return $this->getConfigData(self::PATH_TO_UPLOAD);
    }

    public function getLimitedFileSize()
    {
        return $this->getConfigData(self::FILE_SIZE);
    }
}