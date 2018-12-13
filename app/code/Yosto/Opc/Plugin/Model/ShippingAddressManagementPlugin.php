<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Plugin\Model;

/**
 * Class ShippingAddressManagementPlugin
 * @package Yosto\Opc\Plugin\Model
 */
class ShippingAddressManagementPlugin
{
    protected $_customerSession;
    function __construct(\Magento\Customer\Model\Session $customerSession)
    {
        $this->_customerSession = $customerSession;
    }

    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {

        if ($address->getCustomerAddressId() == null) {
            if (null === $address->getCountryId()) $address->setCountryId('');
            if (null === $address->getCity()) $address->setCity('');
            if (null === $address->getPostcode()) $address->setPostcode('');
            if (null === $address->getRegionId()) $address->setRegionId('');
            if (null === $address->getRegion()) $address->setRegion('');
            foreach ($address->getData() as $key=>$value) {
                if (is_array($address->getData("{$key}")) && count($address->getData("{$key}")) == 0) {
                    $address->setData("{$key}", "");
                }
            }
        }


        if ($this->_customerSession->getCustomerId() != null && $address->getCustomerId() == null) {
            $address->setCustomerId($this->_customerSession->getCustomerId());
        }
    }
}