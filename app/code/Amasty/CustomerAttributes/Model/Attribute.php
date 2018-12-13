<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_CustomerAttributes
 */


namespace Amasty\CustomerAttributes\Model;

class Attribute
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * @var array
     */
    private $ourAttributeCodes;

    /**
     * Attribute constructor.
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
    }

    /**
     * Check if it's attribute is Amasty custom attribute
     *
     * @param string $attributeCode
     * @return bool
     */
    public function isOurAttribute($attributeCode)
    {
        $isOurAttribute = false;

        if (!isset($this->ourAttributeCodes)) {
            $attributesMetaCollection = $this->attributeMetadataDataProvider
                ->loadAttributesCollection(
                    'customer',
                    'amasty_custom_attribute'
                );
            $attributesMetaCollection
                ->join(
                    ['eav_attribute' => $attributesMetaCollection->getTable('eav_attribute')],
                    'main_table.attribute_id = eav_attribute.attribute_id',
                    ['attribute_code']
                )
                ->removeAllFieldsFromSelect();
            $this->ourAttributeCodes = $attributesMetaCollection->getColumnValues('attribute_code');
        }



        if (in_array($attributeCode, $this->ourAttributeCodes)) {
            $isOurAttribute = true;
        }

        return $isOurAttribute;
    }
}
