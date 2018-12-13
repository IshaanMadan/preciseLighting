<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Helper;

use Magento\Checkout\Model\Session;
use Magento\Newsletter\Model\SubscriberFactory;

/**
 * Class Data
 * @package Yosto\Opc\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var string
     */
    const CONFIG_PATH_LAYOUT = 'yosto_opc_checkout/general/layout';


    const GOOGLE_API_KEY = "yosto_opc_checkout/general/google_api_key";

    const SHOW_ORDER_COMMENT = "yosto_opc_checkout/general/show_order_comment";

    const SHOW_NEWSLETTER = "yosto_opc_checkout/general/show_newsletter";

    const NEWSLETTER_DEFAULT_CHECKED = "yosto_opc_checkout/general/newsletter_default_checked";

    const ENABLE_GIFTWRAP =  "yosto_opc_checkout/giftwrap/enable_giftwrap";

    const GIFTWRAP_TYPE = "yosto_opc_checkout/giftwrap/giftwrap_type";

    const GIFTWRAP_AMOUNT = "yosto_opc_checkout/giftwrap/giftwrap_amount";

    const ENABLE_GIFT_MESSAGE = "yosto_opc_checkout/giftmessage/enable_giftmessage";

    /**
     * @var SubscriberFactory
     */
    protected $_subscriberFactory;


    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    public function __construct(
        SubscriberFactory $subscriberFactory,
        Session $checkoutSession,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->_subscriberFactory = $subscriberFactory;
        $this->_priceCurrency = $priceCurrency;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context);
    }

    /**
     * @return array
     */
    public function getLayoutClassNames()
    {
        $classes = $this->scopeConfig->getValue(
            self::CONFIG_PATH_LAYOUT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return explode(' ', $classes);
    }

    public function getGoogleApiKey()
    {
        return $this->scopeConfig->getValue(
            self::GOOGLE_API_KEY,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
	
	public function isOnCheckoutPage()
    {
        return $this->_getRequest()->getRouteName() === 'checkout';
    }

    public function showOrderComment()
    {
        return (bool) $this->scopeConfig->getValue(
            self::SHOW_ORDER_COMMENT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function showNewsletter() {
        return (bool) $this->scopeConfig->getValue(
            self::SHOW_NEWSLETTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function newsletterDefaultChecked() {
        return (bool) $this->scopeConfig->getValue(
            self::NEWSLETTER_DEFAULT_CHECKED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function enableGiftwrap()
    {
        return (bool) $this->scopeConfig->getValue(
            self::ENABLE_GIFTWRAP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getGiftwrapType()
    {
        return $this->scopeConfig->getValue(
            self::GIFTWRAP_TYPE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getGiftwrapAmount()
    {
        return $this->scopeConfig->getValue(
            self::GIFTWRAP_AMOUNT,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function enableGiftMessage()
    {
        return (bool) $this->scopeConfig->getValue(
            self::ENABLE_GIFT_MESSAGE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * @return mixed
     */
    public function hasGiftwrap() {
        return (bool) $this->_checkoutSession->getData('yosto_opc_giftwrap');
    }

    /**
     * @param $email
     */
    public function addSubscriber($email)
    {
        if ($email) {
            $subscriberModel = $this->_subscriberFactory->create()->loadByEmail($email);
            if ($subscriberModel->getId() === null) {
                try {
                    $this->_subscriberFactory->create()->subscribe($email);
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $this->_logger->error($e->getMessage());
                } catch (\Exception $e) {
                    $this->_logger->error($e->getMessage());
                }

            } elseif ($subscriberModel->getData('subscriber_status') != 1) {
                $subscriberModel->setData('subscriber_status', 1);
                try {
                    $subscriberModel->save();
                } catch (\Exception $e) {
                    $this->_logger->error($e->getMessage());
                }
            }
        }
    }

    public function getOrderGiftWrapAmount()
    {
        $amount = $this->getGiftwrapAmount();
        $giftWrapAmount = 0;
        $items = $this->getQuote()->getAllVisibleItems();
        if ($this->getGiftwrapType() == 1) {
            foreach ($items as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }
                $giftWrapAmount += $amount * ($item->getQty());
            }

        } else {
            $giftWrapAmount = $amount;
        }
        $giftWrapAmount = $this->_priceCurrency->convert($giftWrapAmount);

        return $giftWrapAmount;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     */
    public function getQuote()
    {
        if (empty($this->_quote)) {
            $this->_quote = $this->_checkoutSession->getQuote();
        }

        return $this->_quote;
    }
}
