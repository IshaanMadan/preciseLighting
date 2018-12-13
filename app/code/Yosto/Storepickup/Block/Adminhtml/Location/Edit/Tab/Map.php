<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Block\Adminhtml\Location\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Map extends Generic implements TabInterface
{

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('Google Map');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('Google Map');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Prepare form before rendering HTML
     *
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('yosto_storepickup_location');
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('location_');
        $form->setFieldNameSuffix('location');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Google Map')]);
        
        $fieldset->addField(
            'latitude',
            'text',
            ['name' => 'latitude', 'label' => __('Latitude'), 'title' => __('Latitude'), 'required' => true]
        );
        $fieldset->addField(
            'longitude',
            'text',
            ['name' => 'longitude', 'label' => __('Longitude'), 'title' => __('Longitude'), 'required' => true]
        );
       
        /***** Render *****/
        $field = $fieldset->addField(
            'customfield',
            'text',
            ['name'     => 'customfield','title'    => __('Custom Field'),]
        );
        
        $renderer = $this->getLayout()
        ->createBlock('Yosto\Storepickup\Block\Adminhtml\Location\Edit\Tab\Renderer\Customfield');
        $field->setRenderer($renderer);
        /***** Render *****/
        
        $form->setValues($model->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
