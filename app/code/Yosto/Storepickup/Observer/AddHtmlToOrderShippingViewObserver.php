<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class AddHtmlToOrderShippingViewObserver
 * @package Yosto\Storepickup\Observer
 */
class AddHtmlToOrderShippingViewObserver extends AbstractObserver
{


    public function execute(EventObserver $observer)
    {
        if ($this->isActive()) {
            if ($observer->getElementName() == 'order_shipping_view') {
                $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
                $order = $orderShippingViewBlock->getOrder();
                $localeDate = $this->_objectManager->create('\Magento\Framework\Stdlib\DateTime\TimezoneInterface');
                if ($order->getPickupDate() != '0000-00-00 00:00:00') {
                    $formattedDate = $localeDate->formatDateTime(
                        $order->getPickupDate(),
                        \IntlDateFormatter::MEDIUM,
                        \IntlDateFormatter::MEDIUM,
                        null,
                        $localeDate->getConfigTimezone(
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                            $order->getStore()->getCode()
                        )
                    );
                } else {
                    $formattedDate = __('N/A');
                }

                $storeTitle = $this->_locationFactory->create()
                    ->load($order->getLocationId())
                    ->getStoreTitle();

                $pickupDateBlock = $this->_objectManager->create('Magento\Framework\View\Element\Template');
                $pickupDateBlock->setPickupDate($order->getPickupDate());
                $pickupDateBlock->setStoreTitle($storeTitle);
                $pickupDateBlock->setTemplate('Yosto_Storepickup::order_info_shipping_info.phtml');
                $html = $observer->getTransport()->getOutput() . $pickupDateBlock->toHtml();
                $observer->getTransport()->setOutput($html);
            }
        }

    }
}
