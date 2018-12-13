<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\DeliveryDate\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddHtmlToOrderShippingBlockObserver
 * @package Yosto\DeliveryDate\Observer
 */
class AddHtmlToOrderShippingBlockObserver extends AbstractObserver
{
    public function execute(EventObserver $observer)
    {
        if ($this->isActive()) {
            if($observer->getElementName() == 'sales.order.info') {
                $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
                $order = $orderShippingViewBlock->getOrder();
//                $localeDate = $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
//                if($order->getDeliveryDate() != '0000-00-00 00:00:00') {
//                    $formattedDate = $localeDate->formatDateTime(
//                        $order->getDeliveryDate(),
//                        \IntlDateFormatter::MEDIUM,
//                        \IntlDateFormatter::MEDIUM,
//                        null,
//                        $localeDate->getConfigTimezone(
//                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
//                            $order->getStore()->getCode()
//                        )
//                    );
//                } else {
//                    $formattedDate = __('N/A');
//                }

                $deliveryDateBlock = $this->_objectManager->create('Magento\Framework\View\Element\Template');
                $deliveryDateBlock->setDeliveryDate($order->getDeliveryDate());
                $deliveryDateBlock->setDeliveryComment($order->getDeliveryComment());
                $deliveryDateBlock->setTemplate('Yosto_DeliveryDate::order_info_shipping_info.phtml');
                $html = $observer->getTransport()->getOutput() . $deliveryDateBlock->toHtml();
                $observer->getTransport()->setOutput($html);
            }
        }

    }
}