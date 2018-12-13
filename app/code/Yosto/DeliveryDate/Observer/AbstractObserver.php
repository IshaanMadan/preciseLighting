<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\DeliveryDate\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Yosto\DeliveryDate\Helper\ConfigData;

/**
 * Class AbstractObserver
 * @package Yosto\DeliveryDate\Observer
 */
abstract class AbstractObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    protected $_helper;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        ConfigData $helper
    ){
        $this->_helper = $helper;
        $this->_objectManager = $objectmanager;
    }

    protected function isActive() {
            return (bool) $this->_helper->getConfigData('yosto_opc_deliverydate/general/active');
    }
}