<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Observer;

use Magento\Framework\Event\Observer;

/**
 * Class AddOrderAttachmentToAdminOrder
 * @package Yosto\OrderAttachment\Observer
 */
class AddOrderAttachmentToAdminOrder extends AbstractObserver
{
    public function execute(Observer $observer)
    {
        if ($this->getEnable()) {
            if ($observer->getElementName() == 'order_info')
            {
                $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
                $order = $orderShippingViewBlock->getOrder();
                $orderAttachmentBlock = $this->_objectManager->create('Magento\Framework\View\Element\Template');
                $orderAttachmentBlock->setYostoOrderAttachment($order->getYostoOrderAttachment());
                $orderAttachmentBlock->setQuoteId($order->getQuoteId());
                $orderAttachmentBlock->setOrderId($order->getId());
                $orderAttachmentBlock->setTemplate('Yosto_OrderAttachment::order_attachment_info.phtml');
                $html = $observer->getTransport()->getOutput() . $orderAttachmentBlock->toHtml();
                $observer->getTransport()->setOutput($html);
            }
        }
    }

}