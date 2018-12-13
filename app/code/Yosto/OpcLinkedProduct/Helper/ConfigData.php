<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OpcLinkedProduct\Helper;


use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class ConfigData
 * @package Yosto\OpcLinkedProduct\Helper
 */
class ConfigData extends AbstractHelper
{
    const ENABLE = "yosto_opc_linked_product/general/enable";
    const TITLE = "yosto_opc_linked_product/general/title";
    const NUMBER_OF_ITEMS = "yosto_opc_linked_product/general/number_of_items";
    const ENABLE_FOR_SUCCESS_PAGE = "yosto_opc_linked_product/general/enable_for_success_page";
    const LINK_TYPE = "yosto_opc_linked_product/general/link_type";

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

    public function getNumberOfItems()
    {
        return $this->getConfigData(self::NUMBER_OF_ITEMS);
    }

    public function getEnableForSuccessPage()
    {
        return $this->getConfigData(self::ENABLE_FOR_SUCCESS_PAGE);
    }

    public function getLinkType()
    {
        return $this->getConfigData(self::LINK_TYPE);
    }

}