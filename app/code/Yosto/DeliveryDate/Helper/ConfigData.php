<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */


namespace Yosto\DeliveryDate\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class ConfigData
 * @package Yosto\DeliveryDate\Helper
 */
class ConfigData extends AbstractHelper
{
    public function getConfigData($path, $storeId = null)
    {
        return $this->scopeConfig->getValue($path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

}