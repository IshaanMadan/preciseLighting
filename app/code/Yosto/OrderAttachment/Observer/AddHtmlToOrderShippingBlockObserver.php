<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\OrderAttachment\Observer;

use Magento\Framework\Event\Observer as EventObserver;

/**
 * Class AddHtmlToOrderShippingBlockObserver
 * @package Yosto\DeliveryDate\Observer
 */
class AddHtmlToOrderShippingBlockObserver extends AbstractObserver
{
    public function execute(EventObserver $observer)
    {
        if ($this->getEnable()) {
            if($observer->getElementName() == 'sales.order.info') {
                $orderShippingViewBlock = $observer->getLayout()->getBlock($observer->getElementName());
                $order = $orderShippingViewBlock->getOrder();

                $orderAttachmentBlock = $this->_objectManager->create('Magento\Framework\View\Element\Template');
                $orderAttachmentBlock->setYostoOrderAttachment($order->getYostoOrderAttachment());
                $orderAttachmentBlock->setTemplate('Yosto_OrderAttachment::order_attachment_info.phtml');
                $html = $observer->getTransport()->getOutput() . $orderAttachmentBlock->toHtml();
                $observer->getTransport()->setOutput($html);
            }
        }

    }
}