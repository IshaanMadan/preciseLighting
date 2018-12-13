<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Yosto\OrderAttachment\Helper\Data;
use Magento\Checkout\Model\Session;

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

    protected $_checkoutSession;

    /**
     * AbstractObserver constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param Data $helper
     * @param Session $checkoutSession
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        Data $helper,
        Session $checkoutSession
    ){
        $this->_helper = $helper;
        $this->_objectManager = $objectManager;
        $this->_checkoutSession = $checkoutSession;
    }

    public function getEnable() {
            return (bool) $this->_helper->getEnable();
    }
}