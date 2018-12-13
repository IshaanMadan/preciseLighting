<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Model\Config\Source;

/**
 * Class GiftWrap
 * @package Yosto\Opc\Model\Config\Source
 */
class GiftWrap implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return [
            0 => __('Per Order'),
            1 => __('Per Item'),
        ];
    }
}