<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\AddressAutocomplete\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class ConfigData
 * @package Yosto\AddressAutocomplete\Helper
 */
class ConfigData extends AbstractHelper
{
    /**
     * @param null $storeId
     * @return mixed
     */
    public function isEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue("yosto_opc_autocomplete/general/enabled",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param null $storeId
     * @return mixed
     */
    public function getApiKey($storeId = null)
    {
        return $this->scopeConfig->getValue("yosto_opc_checkout/general/google_api_key",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

}