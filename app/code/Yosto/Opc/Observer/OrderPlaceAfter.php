<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderPlaceAfter implements ObserverInterface
{
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $_helper;
    /**
     * OrderPlaceAfter constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Yosto\Opc\Helper\Data $helper
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Yosto\Opc\Helper\Data $helper
    )
    {
        $this->_checkoutSession = $checkoutSession;
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

        $order = $observer->getOrder();
        $comment = $this->_checkoutSession->getData('yosto_opc_order_comment', true);
        if ($comment) {
            $order->addStatusHistoryComment($comment);
        }
        $isSubscriber =  $this->_checkoutSession->getData('yosto_opc_subscribe_newsletter', true);
        if ($isSubscriber) {
            if ($order->getShippingAddress()) {
                $sendEmail = $order->getShippingAddress()->getEmail();
            } elseif ($order->getBillingAddress()) {
                $sendEmail = $order->getBillingAddress()->getEmail();
            } else {
                $sendEmail = '';
            }
            if ($sendEmail) {
                $this->_helper->addSubscriber($sendEmail);
            }
        }
        $this->_checkoutSession->setYostoOpcGiftwrapAmount(null);
        $this->_checkoutSession->setYostoOpcBaseGiftwrapAmount(null);
        $this->_checkoutSession->setYostoOpcGiftwrap(null);
    }
}
