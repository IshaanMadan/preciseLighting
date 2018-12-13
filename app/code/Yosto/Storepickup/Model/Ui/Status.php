<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Model\Ui;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 * @package Yosto\Storepickup\Model\Ui
 */
class Status implements OptionSourceInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['label'=> 'Active', 'value' => 1],
            ['label'=> 'Inactive', 'value' => 0],
        ];
    }

}