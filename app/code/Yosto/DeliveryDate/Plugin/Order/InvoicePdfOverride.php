<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\DeliveryDate\Plugin\Order;


use Magento\Framework\App\ObjectManager;
use Magento\Sales\Model\Order\Pdf\Config;
use Magento\Sales\Model\Order\Pdf\Invoice;

/**
 * Class InvoicePdfOverride
 * @package Yosto\DeliveryDate\Plugin\Order
 */
class InvoicePdfOverride extends Invoice
{
    protected function insertOrder(&$page, $obj, $putOrderId = true)
    {
        parent::insertOrder($page, $obj, $putOrderId);
        $objMng = ObjectManager::getInstance();

        /** @var  $configData  \Yosto\DeliveryDate\Helper\ConfigData */
        $configData = $objMng->create('Yosto\DeliveryDate\Helper\ConfigData');
        $isActive = (bool) $configData->getConfigData('yosto_opc_deliverydate/general/active');
        if ($isActive) {
            if ($configData->getConfigData('yosto_opc_deliverydate/pdf/enable_invoice_pdf') == 1) {
                if ($obj instanceof \Magento\Sales\Model\Order) {
                    $shipment = null;
                    $order = $obj;
                } elseif ($obj instanceof \Magento\Sales\Model\Order\Shipment) {
                    $shipment = $obj;
                    $order = $shipment->getOrder();
                }
                $page->setFillColor(new \Zend_Pdf_Color_Rgb(0.93, 0.92, 0.92));
                $page->setLineColor(new \Zend_Pdf_Color_GrayScale(0.5));
                $page->setLineWidth(0.5);
                $page->drawRectangle(25, $this->y, 570, $this->y - 25);
                $this->_setFontRegular($page, 10);
                $page->setFillColor(new \Zend_Pdf_Color_GrayScale(0));
                $this->_setFontBold($page, 12);
                $page->drawText(__('Delivery Information:'), 35, $this->y - 15, 'UTF-8');
                $this->_setFontRegular($page, 10);
                $page->drawText(__('Delivery Date'), 35, $this->y - 40, 'UTF-8');
                $page->drawText($order->getData('delivery_date'), 275 + 15, $this->y - 40, 'UTF-8');
                $page->drawText(__('Delivery Comments: '), 35, $this->y - 55, 'UTF-8');
                $page->drawText($order->getData('delivery_comment'), 275 + 15, $this->y - 55, 'UTF-8');

                $this->y -= 65;
            }
        }

    }
}