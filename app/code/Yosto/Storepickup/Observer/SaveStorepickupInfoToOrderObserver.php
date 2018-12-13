<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class SaveStorepickupInfoToOrderObserver
 * @package Yosto\Storepickup\Observer
 */
class SaveStorepickupInfoToOrderObserver extends AbstractObserver
{

    /**
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        if ($this->isActive()) {
            $order = $observer->getOrder();
            $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $quoteRepository->get($order->getQuoteId());
            $order->setPickupDate($quote->getData('pickup_date'));
            $order->setLocationId($quote->getData('location_id'));
        }
        return $this;
    }
}
