<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Eav\Helper\Data;
use Magento\Eav\Model\Entity\Attribute;
use Magento\Framework\Exception\LocalizedException;

class Advanced extends Generic
{
    /**
     * Eav data
     *
     * @var Data
     */
    protected $eavData;

    /**
     * @var Yesno
     */
    protected $yesNoDataSource;

    /**
     * @var array
     */
    protected $disableScopeChangeList;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param Yesno $yesNoDataSource
     * @param Data $eavData
     * @param array $disableScopeChangeList
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Config\Model\Config\Source\Yesno $yesNoDataSource,
        \Magento\Eav\Helper\Data $eavData,
        array $disableScopeChangeList = ['sku'],
        array $data = []
    ) {
        $this->yesNoDataSource = $yesNoDataSource;
        $this->eavData = $eavData;
        $this->disableScopeChangeList = $disableScopeChangeList;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Adding product form elements for editing attribute
     *
     * @return $this
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD)
     */
    protected function _prepareForm()
    {
        $attributeObject = $this->getAttributeObject();

        $form = $this->_formFactory->create([
            'data' => [
                'id'     => 'edit_form',
                'action' => $this->getData('action'),
                'method' => 'post'
            ]
        ]);

        $fieldset = $form->addFieldset(
            'advanced_fieldset',
            [
                'legend'      => __('Advanced Field Properties'),
                'collapsable' => false
            ]
        );

        $yesNoOptionsArray = $this->yesNoDataSource->toOptionArray();

        $validateClass = sprintf(
            'validate-code validate-length maximum-length-%d required-entry',
            Attribute::ATTRIBUTE_CODE_MAX_LENGTH
        );
        $fieldset->addField(
            'attribute_code',
            'text',
            [
                'name'  => 'attribute_code',
                'label' => __('Field Code'),
                'title' => __('Field Code'),
                'note'  => __(
                    'This is used internally. Make sure you don\'t use spaces or more than %1 symbols.',
                    Attribute::ATTRIBUTE_CODE_MAX_LENGTH
                ),
                'class' => $validateClass
            ]
        );

        $fieldset->addField(
            'default_value_text',
            'text',
            [
                'name'  => 'default_value_text',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue()
            ]
        );

        $fieldset->addField(
            'note',
            'textarea',
            [
                'name'  => 'note',
                'label' => __('Note'),
                'title' => __('Note'),
                'note'  => __('Maximum 255 chars'),
                'value' => $attributeObject->getDefaultValue()
            ]
        );

        $fieldset->addField(
            'attribute_placement',
            'radios',
            [
                'name'   => 'attribute_placement',
                'label'  => __('Review item sections:'),
                'title'  => __('Review item sections:'),
                'values' => [
                    [
                        'value' => 0,
                        'label' => __('Review content section (main area)')
                    ], [
                        'value' => 1,
                        'label' => __('Customer details section (left bar)')
                    ],
                ],
                'value' => (int) $attributeObject->getDefaultValue(),
                'note'   => __('Text Area and Images are always displayed in the Review Content'),
            ]
        );

        $fieldset->addField(
            'width_field_for_desktop',
            'text',
            [
                'name'  => 'width_field_for_desktop',
                'label' => __('Desktop version width (more 1024px)'),
                'title' => __('Desktop width'),
                'note'  => __('In percents'),
                'value' => $attributeObject->getDefaultValue() ?: 100
            ]
        );

        $fieldset->addField(
            'width_field_for_table',
            'text',
            [
                'name'  => 'width_field_for_table',
                'label' => __('Tablet version width (more 768px)'),
                'title' => __('Tablet width'),
                'note'  => __('In percents'),
                'value' => $attributeObject->getDefaultValue() ?: 100
            ]
        );

        $fieldset->addField(
            'width_field_for_mobile',
            'text',
            [
                'name'  => 'width_field_for_mobile',
                'label' => __('Mobile version width (less 768px)'),
                'title' => __('Mobile width'),
                'note'  => __('In percents'),
                'value' => $attributeObject->getDefaultValue() ?: '100'
            ]
        );

        $fieldset->addField(
        'last_field_in_line',
        'select',
            [
                'name'   => 'last_field_in_line',
                'label'  => __('Last field in line'),
                'title'  => __('Last field in line'),
                'values' => $yesNoOptionsArray,
                'note'   => __('If is set to "Yes", a 1% margin is added between the elements.'),
                'value'  => $attributeObject->getDefaultValue() ?: '1'
            ]
        );

        $fieldset->addField(
            'horizontal_line',
            'select',
            [
                'name' => 'horizontal_line',
                'label' => __('Add a horizontal line after'),
                'title' => __('Add a horizontal line after'),
                'values' => $yesNoOptionsArray,
                'value' => $attributeObject->getDefaultValue() ?: '0'
            ]
        );

        $fieldset->addField(
            'default_value_yesno',
            'select',
            [
                'name' => 'default_value_yesno',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'values' => $yesNoOptionsArray,
                'value' => $attributeObject->getDefaultValue()
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $fieldset->addField(
            'default_value_date',
            'date',
            [
                'name' => 'default_value_date',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue(),
                'date_format' => $dateFormat
            ]
        );

        $fieldset->addField(
            'default_value_textarea',
            'textarea',
            [
                'name' => 'default_value_textarea',
                'label' => __('Default Value'),
                'title' => __('Default Value'),
                'value' => $attributeObject->getDefaultValue()
            ]
        );

//        @TODO: we can probably use this field for frontend validation, but I'm not sure.
//        This works for filling data on the backend
//        May need some other validation rules selection for frontend
//        $fieldset->addField(
//            'frontend_class',
//            'select',
//            [
//                'name' => 'frontend_class',
//                'label' => __('Input Validation for Store Owner'),
//                'title' => __('Input Validation for Store Owner'),
//                'values' => $this->_eavData->getFrontendClasses($attributeObject->getEntityType()->getEntityTypeCode())
//            ]
//        );

//        $fieldset->addField(
//            'is_used_in_grid',
//            'select',
//            [
//                'name' => 'is_used_in_grid',
//                'label' => __('Add to Column Options'),
//                'title' => __('Add to Column Options'),
//                'values' => $yesno,
//                'value' => $attributeObject->getData('is_used_in_grid') ?: 1,
//                'note' => __('Select "Yes" to add this attribute to the list of column options in the product grid.'),
//            ]
//        );

//        $fieldset->addField(
//            'is_visible_in_grid',
//            'hidden',
//            [
//                'name' => 'is_visible_in_grid',
//                'value' => $attributeObject->getData('is_visible_in_grid') ?: 1,
//            ]
//        );

//        $fieldset->addField(
//            'is_filterable_in_grid',
//            'select',
//            [
//                'name' => 'is_filterable_in_grid',
//                'label' => __('Use in Filter Options'),
//                'title' => __('Use in Filter Options'),
//                'values' => $yesno,
//                'value' => $attributeObject->getData('is_filterable_in_grid') ?: 1,
//                'note' => __('Select "Yes" to add this attribute to the list of filter options in the product grid.'),
//            ]
//        );

        if ($attributeObject->getId()) {
            /** @var \Magento\Framework\Data\Form\Element\Select $element */
            $element = $form->getElement('attribute_code');
            $element->setData('disabled', 1);
//            if (!$attributeObject->getIsUserDefined()) {
//                $form->getElement('is_unique')->setDisabled(1);
//            }
        }

        $this->_eventManager->dispatch('review_attribute_form_build', ['form' => $form]);
        $this->setForm($form);
        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function _initFormValues()
    {
        $this->getForm()->addValues($this->getAttributeObject()->getData());
        return parent::_initFormValues();
    }

    /**
     * Retrieve attribute object from registry
     *
     * @return mixed
     */
    protected function getAttributeObject()
    {
        return $this->_coreRegistry->registry('entity_attribute');
    }
}
