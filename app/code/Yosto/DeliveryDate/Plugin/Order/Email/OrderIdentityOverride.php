<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\DeliveryDate\Plugin\Order\Email;


use Magento\Sales\Model\Order\Email\Container\OrderIdentity;

/**
 * Class OrderIdentityOverride
 * @package Yosto\DeliveryDate\Plugin\Order\Email
 */
class OrderIdentityOverride extends OrderIdentity
{
    public function getGuestTemplateId()
    {
        if ($this->getConfigData('enable_order_email') == 1 && $this->isActive()) {
            return $this->getConfigData('order_guest');
        } else {
            return parent::getGuestTemplateId();
        }

    }

    public function getTemplateId()
    {
        if ($this->getConfigData('enable_order_email') == 1 && $this->isActive()) {
            return $this->getConfigData('order');
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