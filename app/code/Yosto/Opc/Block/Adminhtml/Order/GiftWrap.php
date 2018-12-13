<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Block\Adminhtml\Order;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Block\Adminhtml\Order
 */
class GiftWrap extends \Magento\Sales\Block\Adminhtml\Totals
{
    /**
     * Init totals
     */
    public function initTotals()
    {
        parent::_initTotals();
        $orderTotalsBlock = $this->getParentBlock();
        $order = $orderTotalsBlock->getOrder();
        $giftWrapAmount = $order->getYostoOpcGiftwrapAmount();
        $baseGiftWrapAmount = $order->getYostoOpcBaseGiftwrapAmount();
        if ($giftWrapAmount > 0) {
            $orderTotalsBlock->addTotal(new \Magento\Framework\DataObject([
                'code'       => 'gift_wrap',
                'label'      => __('Gift Wrap'),
                'value'      => $giftWrapAmount,
                'base_value' => $baseGiftWrapAmount,
            ]), 'subtotal');
        }
    }
}