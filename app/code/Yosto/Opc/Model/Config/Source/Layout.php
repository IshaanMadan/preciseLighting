<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Opc\Model\Config\Source;

class Layout implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'yosto-opc-col-1', 'label' => __('1 Column (Sticky sidebar)')],
            ['value' => 'yosto-opc-col-2',          'label' => __('2 Columns')],
            ['value' => 'yosto-opc-col-2 collage',      'label' => __('2 Columns (Collage layout)')],
            ['value' => 'yosto-opc-col-3',          'label' => __('3 Columns')],
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = [];
        foreach ($this->toOptionArray() as $item) {
            $result[$item['value']] = $item['label'];
        }
        return $result;
    }
}
