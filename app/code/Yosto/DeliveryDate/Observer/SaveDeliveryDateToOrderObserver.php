<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\DeliveryDate\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Yosto\DeliveryDate\Helper\ConfigData;

/**
 * Class SaveDeliveryDateToOrderObserver
 * @package Yosto\DeliveryDate\Observer
 */
class SaveDeliveryDateToOrderObserver extends AbstractObserver
{
    public function execute(EventObserver $observer)
    {

        if ($this->isActive()) {
            $order = $observer->getOrder();
            $quoteRepository = $this->_objectManager->create('Magento\Quote\Model\QuoteRepository');
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $quoteRepository->get($order->getQuoteId());
            $order->setDeliveryDate($quote->getDeliveryDate());
            $order->setDeliveryComment($quote->getDeliveryComment());
        }
        return $this;
    }
}