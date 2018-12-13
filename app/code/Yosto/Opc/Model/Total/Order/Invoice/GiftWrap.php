<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Model\Total\Order\Invoice;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Model\Total\Order\Invoice
 */
class GiftWrap extends \Magento\Sales\Model\Order\Total\AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Invoice $invoice
     *
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $invoice->setYostoOpcGiftwrapAmount(0);
        $giftWrapAmount = $invoice->getOrder()->getYostoOpcGiftwrapAmount();
        $baseGiftWrapAmount = $invoice->getOrder()->getYostoOpcBaseGiftwrapAmount();
        if ($giftWrapAmount) {
            $invoice->setYostoOpcGiftwrapAmount($giftWrapAmount);
            $invoice->setYostoOpcBaseGiftwrapAmount($baseGiftWrapAmount);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $giftWrapAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseGiftWrapAmount);
        }

        return $this;
    }
}
