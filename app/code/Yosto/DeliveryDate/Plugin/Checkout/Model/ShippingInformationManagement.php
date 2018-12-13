<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\DeliveryDate\Plugin\Checkout\Model;


use Yosto\DeliveryDate\Helper\ConfigData;

/**
 * Class ShippingInformationManagement
 * @package Yosto\DeliveryDate\Plugin\Checkout\Model
 */
class ShippingInformationManagement
{
    protected $quoteRepository;
    protected $_helper;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        ConfigData $helper
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
        $isActive = (bool) $this->_helper->getConfigData('yosto_opc_deliverydate/general/active');
        if ($isActive) {
            $extAttributes = $addressInformation->getExtensionAttributes();
            $deliveryDate = $extAttributes->getDeliveryDate();
            $deliveryComment = $extAttributes->getDeliveryComment();
            $quote = $this->quoteRepository->getActive($cartId);
            $quote->setDeliveryDate($deliveryDate);
            $quote->setDeliveryComment($deliveryComment);
        }
    }
}