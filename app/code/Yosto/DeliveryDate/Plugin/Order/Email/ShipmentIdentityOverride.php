<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\DeliveryDate\Plugin\Order\Email;


use Magento\Sales\Model\Order\Email\Container\ShipmentIdentity;

/**
 * Class ShipmentIdentityOverride
 * @package Yosto\DeliveryDate\Plugin\Order\Email
 */
class ShipmentIdentityOverride extends ShipmentIdentity
{
    public function getGuestTemplateId()
    {
        if ($this->getConfigData('enable_shipment_email') == 1 && $this->isActive()) {
            return $this->getConfigData('shipment_guest');
        } else {
            return parent::getGuestTemplateId();
        }

    }

    public function getTemplateId()
    {
        if ($this->getConfigData('enable_shipment_email') == 1 && $this->isActive()) {
            return $this->getConfigData('shipment');
        } else {
            return parent::getTemplateId();
        }
    }

    public function getConfigData($path) {
        return $this->getConfigValue(
            'yosto_opc_deliverydate/email/' . $path,
            $this->getStore()->getStoreId()
        );
    }
    public function isActive()
    {
        return (bool) $this->getConfigValue(
            'yosto_opc_deliverydate/general/active',
            $this->getStore()->getStoreId()
        );
    }
}