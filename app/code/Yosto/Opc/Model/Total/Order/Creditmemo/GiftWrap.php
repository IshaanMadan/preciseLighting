<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Model\Total\Order\Creditmemo;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Model\Total\Order\Creditmemo
 */
class GiftWrap extends \Magento\Sales\Model\Order\Total\AbstractTotal
{
    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $creditmemo->setYostoOpcGiftwrapAmount(0);
        $giftWrapAmount = $creditmemo->getOrder()->getYostoOpcGiftwrapAmount();
        $baseGiftWrapAmount = $creditmemo->getOrder()->getYostoOpcBaseGiftwrapAmount();
        if ($giftWrapAmount) {
            $creditmemo->setYostoOpcGiftwrapAmount($giftWrapAmount);
            $creditmemo->setYostoOpcBaseGiftwrapAmount($baseGiftWrapAmount);
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $giftWrapAmount);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseGiftWrapAmount);
        }

        return $this;
    }
}
