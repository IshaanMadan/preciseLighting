<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Model\Total\Quote;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Model\Total\Quote
 */
class GiftWrap extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{

    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $_helper;
    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $priceCurrency;


    /**
     * GiftWrap constructor.
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Yosto\Opc\Helper\Data $helper
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Yosto\Opc\Helper\Data $helper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
    )
    {
        $this->setCode('gift_wrap');
        $this->priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
    }


    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);
        $active = $this->_helper->enableGiftwrap();
        if (!$active) {
            return $this;
        }

        $giftWrap = $this->_checkoutSession->getData('yosto_opc_giftwrap');
        if (!$giftWrap) {
            return $this;
        }

        $items = $quote->getAllVisibleItems();
        if (!count($items)) {
            return $this;
        }

        $giftWrapType = $this->_helper->getGiftwrapType();
        $giftWrapAmount = $this->_helper->getGiftwrapAmount();
        $baseWrapTotal = 0;
        if ($giftWrapType == 1) {
            foreach ($items as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
                $baseWrapTotal += $giftWrapAmount * ($item->getQty());
            }
        } else {
            $baseWrapTotal = $giftWrapAmount;
        }
        $wrapTotal = $this->priceCurrency->convert($baseWrapTotal);
        $this->_checkoutSession->setData('yosto_opc_giftwrap_amount', $wrapTotal);
        $this->_checkoutSession->setData('yosto_opc_base_giftwrap_amount', $baseWrapTotal);
        $quote->setYostoOpcGiftwrapAmount($wrapTotal);
        $quote->setYostoOpcBaseGiftwrapAmount($baseWrapTotal);
        $total->setYostoOpcGiftwrapAmount($wrapTotal);
        $total->setYostoOpcBaseGiftwrapAmount($baseWrapTotal);
        $total->setGrandTotal($total->getGrandTotal() + $total->getYostoOpcGiftwrapAmount());
        $total->setBaseGrandTotal($total->getBaseGrandTotal() + $total->getYostoOpcBaseGiftwrapAmount());

        return $this;
    }


    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array|null
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $result = null;
        $amount = $total->getYostoOpcGiftwrapAmount();
        if ($amount != 0) {
            $result = [
                'code'  => $this->getCode(),
                'title' => __('Gift Wrap'),
                'value' => $amount,
            ];
        }

        return $result;
    }
}
