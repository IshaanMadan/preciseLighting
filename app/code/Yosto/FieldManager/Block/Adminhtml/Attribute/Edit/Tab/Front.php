<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Config\Model\Config\Source\Yesno;
use Yosto\FieldManager\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

/**
 * Config visible in forms for customer/address attribute
 *
 * Class Front
 * @package Yosto\FieldManager\Block\Adminhtml\Attribute\Edit\Tab
 */
class Front extends Generic
{
    /**
     * @var Yesno
     */
    protected $_yesNo;

    /**
     * @var PropertyLocker
     */
    private $propertyLocker;


    /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Yesno $yesNo
     * @param PropertyLocker $propertyLocker
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        PropertyLocker $propertyLocker,
        array $data = []
    )
    {
        $this->_yesNo = $yesNo;
        $this->propertyLocker = $propertyLocker;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var Attribute $attributeObject */
        $attributeObject = $this->_coreRegistry->registry('address_entity_attribute');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );


        $yesno = $this->_yesNo->toOptionArray();

        $fieldset = $form->addFieldset(
            'front_fieldset',
            ['legend' => __('Storefront Properties'), 'collapsable' => $this->getRequest()->has('popup')]
        );
        $fieldset->addField(
            'entity_type_id',
            'hidden',
            [
                'name' => 'entity_type_id',
                'value' => $this->getRequest()->getParam('entity_type_id')
            ]
        );

        $fieldset->addField(
            'is_visible',
            'select',
            [
                'name' => 'is_visible',
                'label' => __('Visible'),
                'values' => $yesno,
                'note' => "Select 'yes' to show attribute on the admin page and the frontend page"
            ]
        );

        $fieldset->addField(
            'sort_order',
            'text',
            [
                'name' => 'sort_order',
                'label' => __('Sorting Order'),
                'note' => __('The order to display field on frontend'),
            ]
        );

        $this->setForm($form);
        $form->setValues($attributeObject->getData());
        $this->propertyLocker->lock($form);
        return parent::_prepareForm();

    }
}