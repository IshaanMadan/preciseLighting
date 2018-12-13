<?php
/**
 * @copyright Copyright (c) 2017 www.tigren.com
 */

namespace Tigren\Ajaxcart\Block;

class CartInfo extends \Magento\Framework\View\Element\Template
{
    protected $_cart;
    protected $_checkoutSession;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Checkout\Model\Cart $cart,
        array $data
    )
    {
        parent::__construct($context, $data);
        $this->_cart = $cart;
        $this->_checkoutSession = $checkoutSession;
    }

    public function getAddedProduct() {
        $items = $this->_cart->getItems();
        $result = "";
        if (count($items) > 0) {
            $item = $items->getLastItem();
            $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
            $customOptions = $options['options'];
            if (!empty($customOptions)) {
                foreach ($customOptions as $option) {
                    $result .= $option['label'] . ": " . $option['value'] . "<br/>";
                }
            }
        }

        return $result;
    }

    public function getItemsCount()
    {
        return $this->_cart->getItemsCount();
    }

    public function getItemsQty()
    {
        return $this->_cart->getItemsQty();
    }

    public function getSubTotal()
    {
        return $this->_cart->getQuote()->getSubtotal();
    }
}