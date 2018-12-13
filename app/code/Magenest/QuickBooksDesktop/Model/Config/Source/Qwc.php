<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Model\Config\Source;

/**
 * Class Qwc
 * @package Magenest\QuickBooksDesktop\Model\Config\Source
 */
class Qwc implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => '4', 'label' => __('Query Company')],
            ['value' => '1', 'label' => __('Default') ],
        ];
    }
}
