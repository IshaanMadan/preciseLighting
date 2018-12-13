<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\ImageLoader\Model\Config\Source;

/**
 * Class ImageTypes
 * @package MageWorkshop\ImageLoader\Model\Config\Source
 */
class ImageTypes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'jpg', 'label' => __('jpg')],
            ['value' => 'jpeg', 'label' => __('jpeg')],
            ['value' => 'gif', 'label' => __('gif')],
            ['value' => 'png', 'label' => __('png')]
        ];
    }
}