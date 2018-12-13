<?php
/**
 * Doyenhub_StreetAddress extension
 * NOTICE OF LICENSE
 * This source file is subject to the Doyenhub License
 * that is bundled with this package in the file LICENSE.txt.
 * @category  Doyenhub_Extensions
 * @package   Doyenhub_StreetAddress
 * @copyright Copyright (c) 2017
 * @author Doyenhub Developer <support@doyenhub.com>
 */
namespace Doyenhub\StreetAddress\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper {

    /**
     *
     * @var \Magento\Store\Model\StoreManagerInterface 
     */
    protected $storeManager;
    /**
     *
     * @var \Magento\Framework\ObjectManagerInterface 
     */
    protected $objectManager;

    const XML_PATH_PLACEHOLDER = 'doyenhub_streetaddress/general/placeholder';
    const XML_PATH_STATUS = 'doyenhub_streetaddress/general/active';

    /**
     * constructor
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        ObjectManagerInterface $objectManager,
        StoreManagerInterface $storeManager
    ) {
        $this->objectManager = $objectManager;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * configuration data
     * 
     * @param string $field
     * @param int $storeId
     * @return string
     */
    public function getConfigValue($field, $storeId = null) {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    /**
     * store id
     * 
     * @return int
     */
    public function getStoreId() {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * enable/disable module
     * 
     * @param int $storeId
     * @return int
     */
    public function getStatus($storeId = null) {
        return $this->getConfigValue(self::XML_PATH_STATUS, $storeId);
    }

    /**
     * placeholder data
     * 
     * @param int $storeId
     * @return string
     */
    public function getPlaceholder($storeId = null) {
        return $this->getConfigValue(self::XML_PATH_PLACEHOLDER, $storeId);
    }
}
