<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Model\Ui;


use Magento\Framework\Data\OptionSourceInterface;
use Yosto\Storepickup\Model\LocationFactory;

/**
 * Class Location
 * @package Yosto\Storepickup\Model\Ui
 */
class Location implements OptionSourceInterface
{
    protected $_locationFactory;

    function __construct(
      LocationFactory  $locationFactory
    ) {
        $this->_locationFactory = $locationFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $locationCollection = $this->_locationFactory->create()->getCollection();
        $location = [];
        foreach ($locationCollection as $item) {
            $location[] = ['label' => $item->getData('store_title'), 'value' => $item->getData('location_id')];
        }
        return $location;
    }

}