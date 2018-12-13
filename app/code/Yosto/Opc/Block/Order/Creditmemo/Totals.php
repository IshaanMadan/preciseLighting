<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Block\Order\Creditmemo;

/**
 * Class Totals
 * @package Yosto\Opc\Block\Order\Creditmemo
 */
class Totals extends \Magento\Framework\View\Element\AbstractBlock
{
    /**
     * Init totals
     *
     */
    public function initTotals()
    {
        $creditmemoTotalBlock = $this->getParentBlock();
        $creditmemo = $creditmemoTotalBlock->getCreditmemo();
        if ($creditmemo->getYostoOpcGiftwrapAmount() > 0) {
            $creditmemoTotalBlock->addTotal(new \Magento\Framework\DataObject([
                'code'       => 'gift_wrap',
                'label'      => __('Gift Wrap'),
                'value'      => $creditmemo->getYostoOpcGiftwrapAmount(),
                'base_value' => $creditmemo->getYostoOpcBaseGiftwrapAmount(),
            ]), 'subtotal');
        }
    }

}