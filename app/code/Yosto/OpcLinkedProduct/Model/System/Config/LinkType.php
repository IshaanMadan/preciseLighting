<?php
/**
 * Created by PhpStorm.
 * User: nghiata
 * Date: 19/04/2018
 * Time: 16:19
 */

namespace Yosto\OpcLinkedProduct\Model\System\Config;


use Magento\Catalog\Model\Product\Link;
use Magento\Framework\Option\ArrayInterface;

class LinkType implements ArrayInterface
{
    public function toOptionArray()
    {
        return [
            [
                "value" => Link::LINK_TYPE_RELATED,
                "label" => __('Relate')
            ],
            [
                "value" => Link::LINK_TYPE_UPSELL,
                "label" => __('Up-sell')
            ],
            [
                "value" => Link::LINK_TYPE_CROSSSELL,
                "label" => __('Cross-sell')
            ]
        ];
    }

}