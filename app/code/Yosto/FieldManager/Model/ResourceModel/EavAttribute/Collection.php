<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\FieldManager\Model\ResourceModel\EavAttribute;


use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package Yosto\FieldManager\Model\ResourceModel\EavAttribute
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'attribute_id';

    protected function _construct()
    {
        $this->_init(
            'Yosto\FieldManager\Model\EavAttribute',
            'Yosto\FieldManager\Model\ResourceModel\EavAttribute'
        );
    }



    public function getAttributeTypeById($attributeId)
    {
        $eavEntityTypeTable = $this->getTable('eav_entity_type');

        $this->getSelect()->join(
            $eavEntityTypeTable . ' as type_table',
            "main_table.entity_type_id = type_table.entity_type_id",
            ['entity_type_code']
        )->where("main_table.attribute_id = {$attributeId}");
        return $this;
    }
}