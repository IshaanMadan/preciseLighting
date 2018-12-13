<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class QuoteSubmitSuccess
 * @package Yosto\Opc\Observer
 */
class QuoteSubmitSuccess implements ObserverInterface
{
    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $_helper;


    /**
     * QuoteSubmitSuccess constructor.
     * @param \Yosto\Opc\Helper\Data $helper
     */
    public function __construct(\Yosto\Opc\Helper\Data $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     *
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();
        $this->_helper->sendNewOrderEmail($order);
    }
}
