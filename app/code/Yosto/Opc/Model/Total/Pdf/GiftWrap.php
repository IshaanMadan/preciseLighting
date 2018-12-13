<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Model\Total\Pdf;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Model\Total\Pdf
 */
class GiftWrap extends \Magento\Sales\Model\Order\Pdf\Total\DefaultTotal
{

    /**
     * @return array
     */
    public function getTotalsForDisplay()
    {

        $amount = $this->getOrder()->formatPriceTxt($this->getOrder()->getYostoOpcGiftwrapAmount());
        $fontSize = $this->getFontSize() ? $this->getFontSize() : 7;
        $totals = [[
            'label'     => __('Gift Wrap:'),
            'amount'    => $amount,
            'font_size' => $fontSize,]];

        return $totals;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->getOrder()->getYostoOpcGiftwrapAmount();
    }

}
