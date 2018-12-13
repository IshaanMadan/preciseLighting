<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain;
use Yosto\FieldManager\Block\Adminhtml\Attribute\PropertyLocker;

/**
 * Show main form to config attribute
 *
 * Class Main
 * @package Yosto\FieldManager\Block\Adminhtml\Attribute\Edit\Tab
 */
class Main extends AbstractMain
{/**
 * Preparing default form elements for editing attribute
 *
 * @return $this
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
    protected function _prepareForm()
    {
        $attributeObject = $this->getAttributeObject();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Attribute Properties')]);

        if ($attributeObject->getAttributeId()) {
            $fieldset->addField('attribute_id', 'hidden', ['name' => 'attribute_id']);
        }

        $this->_addElementTypes($fieldset);

        $yesno = $this->_yesnoFactory->create()->toOptionArray();

        $labels = $attributeObject->getFrontendLabel();
        $fieldset->addField(
            'attribute_label',
            'text',
            [
                'name' => 'frontend_label[0]',
                'label' => __('Default Label'),
                'title' => __('Default label'),
                'required' => true,
                'value' => is_array($labels) ? $labels[0] : $labels
            ]
        );


        $fieldset->addField(
            'is_required',
            'select',
            [
                'name' => 'is_required',
                'label' => __('Values Required'),
                'title' => __('Values Required'),
                'values' => $yesno
            ]
        );

        $this->setForm($form);

        return $this;
    }

    /**
     * Retrieve additional element types for product attributes
     *
     * @return array
     */
//    protected function _getAdditionalElementTypes()
//    {
//        return ['apply' => 'Yosto\FieldManager\Block\Adminhtml\Attribute\Helper\Form\Apply'];
//    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Eav\Attribute|mixed
     */
    public function getAttributeObject()
    {
        if (null === $this->_attribute) {
            return $this->_coreRegistry->registry('address_entity_attribute');
        }
        return $this->_attribute;
    }
}