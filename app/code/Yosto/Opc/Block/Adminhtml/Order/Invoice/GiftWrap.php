<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Block\Adminhtml\Order\Invoice;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Block\Adminhtml\Order\Invoice
 */
class GiftWrap extends \Magento\Sales\Block\Adminhtml\Totals
{
    /**
     * Init totals
     */
    public function initTotals()
    {
        $totalsBlock = $this->getParentBlock();
        $invoice = $totalsBlock->getInvoice();
        $giftWrapAmount = $invoice->getYostoOpcGiftwrapAmount();
        $baseGiftWrapAmount = $invoice->getYostoOpcBaseGiftwrapAmount();
        if ($giftWrapAmount > 0) {
            $totalsBlock->addTotal(new \Magento\Framework\DataObject([
                'code'       => 'gift_wrap',
                'label'      => __('Gift Wrap'),
                'value'      => $giftWrapAmount,
                'base_value' => $baseGiftWrapAmount,
            ]), 'subtotal');
        }
    }
}