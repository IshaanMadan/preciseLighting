<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Observer;

use Magento\GiftMessage\Helper\Message;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class GiftMessageConfig
 * @package Yosto\Opc\Observer
 */
class GiftMessageConfig implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    

    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    protected $_resourceConfig;

    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $_helper;

    /**
     * GiftMessageConfig constructor.
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     * @param \Yosto\Opc\Helper\Data $helper
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Yosto\Opc\Helper\Data $helper
    ) {
        $this->_storeManager = $storeManager;
        $this->_resourceConfig = $resourceConfig;
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $scopeId = 1;
        $isGiftMessage = $this->_helper->enableGiftMessage() ? 1 : 0;

        $this->_resourceConfig->saveConfig(
            Message::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ORDER,
            $isGiftMessage,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeId
        );
        $this->_resourceConfig->saveConfig(
            Message::XPATH_CONFIG_GIFT_MESSAGE_ALLOW_ITEMS,
            $isGiftMessage,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
            $scopeId
        );
    }
}
