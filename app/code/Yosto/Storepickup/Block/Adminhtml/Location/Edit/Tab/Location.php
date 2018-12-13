<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Block\Adminhtml\Location\Edit\Tab;

class Location extends \Magento\Backend\Block\Widget\Form\Generic
implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    
    protected $_countryFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Directory\Model\Config\Source\Country $countryFactory,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_countryFactory = $countryFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /** @var \yosto\Storepickup\Model\Location $location */
        $location = $this->_coreRegistry->registry('yosto_storepickup_location');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('location_');
        $form->setFieldNameSuffix('location');
        
        $yesno = [['value' => 1, 'label' => __('Enabled')], ['value' => 0, 'label' => __('Disabled')]];
        
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('Location Information'),
                'class'  => 'fieldset-wide'
            ]
        );
        if ($location->getId()) {
            $fieldset->addField(
                'location_id',
                'hidden',
                ['name' => 'location_id']
            );
        }
        $fieldset->addField(
            'store_title',
            'text',
            [
                'name'  => 'store_title',
                'label' => __('Store Title'),
                'title' => __('Store Title'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'address',
            'text',
            [
                'name'  => 'address',
                'label' => __('Address'),
                'title' => __('Address'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'city',
            'text',
            [
                'name'  => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'state',
            'text',
            [
                'name'  => 'state',
                'label' => __('State'),
                'title' => __('State'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'pincode',
            'text',
            [
                'name'  => 'pincode',
                'label' => __('Pincode'),
                'title' => __('Pincode'),
                'required' => true,
            ]
        );
        $optionsc=$this->_countryFactory->toOptionArray();
        $country = $fieldset->addField(
            'country',
            'select',
            [
                'name' => 'country',
                'label' => __('Country'),
                'title' => __('Country'),
                'values' => $optionsc,
            ]
        );
        $fieldset->addField(
            'phone',
            'text',
            [
                'name'  => 'phone',
                'label' => __('Phone'),
                'title' => __('Phone'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'email',
            'text',
            [
                'name'  => 'email',
                'label' => __('Email'),
                'title' => __('Email'),
                'required' => true,
            ]
        );
        $fieldset->addField(
            'image',
            'image',
            [
            'title' => __('Store Image'),
            'label' => __('Store Image'),
            'name' => 'image',
            'note' => 'Allow image type: jpg, jpeg, gif, png',
            ]
        );
        
        $fieldset->addField(
            'is_enable',
            'select',
            [
                'name' => 'is_enable',
                'label' => __('Status'),
                'title' => __('Status'),
                'values' => $yesno
            ]
        );

        $locationData = $this->_session->getData('storepickup_location_data', true);
        if ($locationData) {
            $location->addData($locationData);
        }
        $form->addValues($location->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Location');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->getTabLabel();
    }

    /**
     * Can show tab in tabs
     *
     * @return boolean
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden
     *
     * @return boolean
     */
    public function isHidden()
    {
        return false;
    }
}
