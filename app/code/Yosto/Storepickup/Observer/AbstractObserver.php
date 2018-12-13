<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Yosto\Storepickup\Helper\Data;
use Yosto\Storepickup\Model\LocationFactory;

/**
 * Class AbstractObserver
 * @package Yosto\Storepickup\Observer
 */
abstract class AbstractObserver implements ObserverInterface
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    protected $_helper;

    protected $_locationFactory;
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectmanager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectmanager,
        Data $helper,
        LocationFactory $locationFactory
    ){
        $this->_helper = $helper;
        $this->_objectManager = $objectmanager;
        $this->_locationFactory = $locationFactory;
    }
    protected function isActive() {
            return (bool) $this->_helper->getConfig('carriers/storepickup/active');
    }
}