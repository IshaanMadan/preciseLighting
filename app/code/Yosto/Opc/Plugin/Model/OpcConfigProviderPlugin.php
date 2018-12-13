<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Plugin\Model;

class OpcConfigProviderPlugin
{
    /**
     * @var \Yosto\Opc\Helper\Data
     */
    protected $helper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Magento\Quote\Api\PaymentMethodManagementInterface
     */
    protected $paymentMethodManagement;

    /**
     * @param \Yosto\Opc\Helper\Data $helper
     */
    public function __construct(
        \Yosto\Opc\Helper\Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Quote\Api\PaymentMethodManagementInterface $paymentMethodManagement
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->paymentMethodManagement = $paymentMethodManagement;
    }

    /**
     * @param \Magento\Checkout\Model\DefaultConfigProvider $subject
     * @param array $result
     * @return string
     */
    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        array $result
    ) {

        if (empty($result['paymentMethods'])) {
            $quote = $this->checkoutSession->getQuote();
            if (!$quote->getIsVirtual()) {
                foreach ($this->paymentMethodManagement->getList($quote->getId()) as $paymentMethod) {
                    $result['paymentMethods'][] = [
                        'code' => $paymentMethod->getCode(),
                        'title' => $paymentMethod->getTitle()
                    ];
                }
            }
        }
        $result['yosto_opc'] = [
            'google_api_key' => $this->helper->getGoogleApiKey(),
            'show_order_comment' => $this->helper->showOrderComment(),
            'show_newsletter'  => $this->helper->showNewsletter(),
            'newsletter_default_checked' => $this->helper->newsletterDefaultChecked(),
            'enable_giftwrap' => $this->helper->enableGiftwrap(),
            'giftwrap_amount' => $this->helper->getGiftwrapAmount(),
            'giftwrap_type' => $this->helper->getGiftwrapType(),
            'has_giftwrap' => $this->helper->hasGiftwrap(),
            'enable_gift_message' => $this->helper->enableGiftMessage()
        ];
        return $result;
    }
}
