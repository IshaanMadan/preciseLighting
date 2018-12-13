<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Model\ResourceModel\Location;

/**
 * Class Collection
 * @package Yosto\Storepickup\Model\ResourceModel\Location
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'location_id';

    protected function _construct()
    {
        $this->_init('Yosto\Storepickup\Model\Location', 'Yosto\Storepickup\Model\ResourceModel\Location');
    }
}
