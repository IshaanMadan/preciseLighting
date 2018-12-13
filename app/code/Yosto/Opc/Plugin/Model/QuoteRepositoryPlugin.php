<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Plugin\Model;

/**
 * Class QuoteRepositoryPlugin
 * @package Yosto\Opc\Plugin\Model
 */
class QuoteRepositoryPlugin
{
    protected $_customerSession;
    function __construct(\Magento\Customer\Model\Session $customerSession)
    {
        $this->_customerSession = $customerSession;
    }
    /**
     * {@inheritdoc}
     */
    public function beforeSave(
        \Magento\Quote\Model\QuoteRepository $subject,
        \Magento\Quote\Api\Data\CartInterface $quote
    ) {

        $billingAddress = $quote->getBillingAddress();
        if ($billingAddress->getCustomerAddressId() == null) {
            if (null === $billingAddress->getCountryId()) $billingAddress->setCountryId('');
            if (null === $billingAddress->getCity()) $billingAddress->setCity('');
            if (null === $billingAddress->getPostcode()) $billingAddress->setPostcode('');
            if (null === $billingAddress->getRegionId()) $billingAddress->setRegionId('');
            if (null === $billingAddress->getRegion()) $billingAddress->setRegion('');
            foreach ($billingAddress->getData() as $key => $value) {
                if (is_array($billingAddress->getData("{$key}")) && count($billingAddress->getData("{$key}")) == 0) {
                    $billingAddress->setData("{$key}", "");
                }
            }
        }
        if ($this->_customerSession->getCustomerId() != null && $billingAddress->getCustomerId() == null) {
            $billingAddress->setCustomerId($this->_customerSession->getCustomerId());
        }

        $quote->setBillingAddress($billingAddress);
    }
}