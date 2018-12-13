<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Model\ResourceModel\Setup;

use Magento\Eav\Model\Entity\Setup\PropertyMapperAbstract;

class PropertyMapper extends PropertyMapperAbstract
{
    /**
     * Map input attribute properties to storage representation
     *
     * @param array $input
     * @param int $entityTypeId
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function map(array $input, $entityTypeId)
    {
        return [
            'additional_data' => $this->_getValue($input, 'additional_data', ''),
            'attribute_placement' => $this->_getValue($input, 'attribute_placement', 0),
            'attribute_visual_settings' => $this->_getValue($input, 'attribute_visual_settings', '')
        ];
    }
}
