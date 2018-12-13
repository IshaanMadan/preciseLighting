<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Observer;

use Magento\Framework\Event\Observer;
/**
 * Class SaveOrderAttachment
 * @package Yosto\OrderAttachment\Observer
 */
class SaveOrderAttachment extends AbstractObserver
{

    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();

        if ($order->getQuoteId()) {
            $attachment = $this->_checkoutSession->getYostoOrderAttachment();
            if ($attachment) {
                $order->setYostoOrderAttachment($attachment);
            }
        }

        $this->_checkoutSession->setYostoOrderAttachment(null);

        return $this;
    }

}