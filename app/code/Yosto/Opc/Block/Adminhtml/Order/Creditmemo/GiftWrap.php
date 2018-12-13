<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Block\Adminhtml\Order\Creditmemo;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Block\Adminhtml\Order\Creditmemo
 */
class GiftWrap extends \Magento\Sales\Block\Adminhtml\Totals
{
    /**
     * Init totals
     */
    public function initTotals()
    {
        $totalsBlock = $this->getParentBlock();
        $creditmemo = $totalsBlock->getCreditmemo();
        $giftWrapAmount = $creditmemo->getYostoOpcGiftwrapAmount();
        $baseGiftWrapAmount = $creditmemo->getYostoOpcBaseGiftwrapAmount();
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