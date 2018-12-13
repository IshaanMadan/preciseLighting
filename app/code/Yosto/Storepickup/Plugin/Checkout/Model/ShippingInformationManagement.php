<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Plugin\Checkout\Model;

use Yosto\Storepickup\Helper\Data;

/**
 * Class ShippingInformationManagement
 * @package Yosto\Storepickup\Plugin\Checkout\Model
 */
class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var Data
     */
    protected $_helper;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        Data $helper
    ) {
        $this->_helper = $helper;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    ) {
        $isActive = (bool) $this->_helper->getConfig('carriers/storepickup/active');
        if ($isActive) {
            $extAttributes = $addressInformation->getExtensionAttributes();
            $pickupDate = $extAttributes->getPickupDate();
            $locationId = $extAttributes->getLocationId();
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setPickupDate($pickupDate);
            $quote->setLocationId($locationId);
        }
    }
}
