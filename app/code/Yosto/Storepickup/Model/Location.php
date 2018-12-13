<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Model;
/**
 * Class Location
 * @package Yosto\Storepickup\Model
 */
class Location extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Yosto\Storepickup\Model\ResourceModel\Location');
    }

}
