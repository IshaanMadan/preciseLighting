<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_CustomerAttributes
 */


namespace Amasty\CustomerAttributes\Plugin\Sales\Block\Adminhtml\Order\Create\Form;

class Account
{
    /**
     * @var \Magento\Customer\Model\AttributeMetadataDataProvider
     */
    private $attributeMetadataDataProvider;

    /**
     * Account constructor.
     * @param \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
     */
    public function __construct(
        \Magento\Customer\Model\AttributeMetadataDataProvider $attributeMetadataDataProvider
    ) {
        $this->attributeMetadataDataProvider = $attributeMetadataDataProvider;
    }

    /**
     * @param \Magento\Sales\Block\Adminhtml\Order\Create\Form\Account $subject
     * @param $result
     * @return mixed
     */
    public function afterGetFormValues(
        $subject,
        $result
    ) {
        $attributes = $this->attributeMetadataDataProvider->loadAttributesCollection(
            'customer',
            'customer_attributes_registration'
        );

        foreach ($attributes as $attribute) {
            if ($attribute->getDefaultValue() !== null) {
                $result[$attribute->getAttributeCode()] = $attribute->getDefaultValue();
            }
        }

        return $result;
    }
}
