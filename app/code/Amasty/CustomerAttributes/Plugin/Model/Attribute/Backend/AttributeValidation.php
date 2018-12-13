<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_CustomerAttributes
 */


namespace Amasty\CustomerAttributes\Plugin\Model\Attribute\Backend;

class AttributeValidation
{
    /**
     * @var \Amasty\CustomerAttributes\Block\Customer\Form\Attributes
     */
    private $formAttributes;

    /**
     * AttributeValidation constructor.
     * @param \Amasty\CustomerAttributes\Block\Customer\Form\Attributes $formAttributes
     */
    public function __construct(
        \Amasty\CustomerAttributes\Block\Customer\Form\Attributes $formAttributes
    ) {
        $this->formAttributes = $formAttributes;
    }

    /**
     * Hide our attributes according to configurations
     *
     * @param \Magento\Eav\Model\Entity\Attribute\Backend\Increment $subject
     * @param \Magento\Customer\Model\Customer $object
     */
    public function beforeValidate(
        $subject,
        $object
    ) {
        /** @var \Magento\Customer\Model\Attribute $attribute */
        $attribute = $subject->getAttribute();

        if (!$this->formAttributes->isAttributeVisible($attribute)) {
            $attribute->setIsVisible(false);
        }
    }
}
