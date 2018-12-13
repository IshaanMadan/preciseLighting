<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class SalesModelServiceQuoteSubmitBefore
 * @package Yosto\Opc\Observer
 */
class SalesModelServiceQuoteSubmitBefore implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * SalesModelServiceQuoteSubmitBefore constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession
    ){
        $this->_checkoutSession = $checkoutSession;
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
        $quote = $observer->getQuote();
        $comment = $this->_checkoutSession->getData('yosto_opc_order_comment');
        $order->setData('yosto_opc_order_comment', $comment);
        $order->setYostoOpcGiftwrapAmount($quote->getYostoOpcGiftwrapAmount())
            ->setYostoOpcBaseGiftwrapAmount($quote->getYostoOpcBaseGiftwrapAmount());
    }
}
