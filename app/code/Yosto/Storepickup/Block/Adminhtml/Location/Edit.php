<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Block\Adminhtml\Location;

/**
 * Class Edit
 * @package Yosto\Storepickup\Block\Adminhtml\Location
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Block\Widget\Context $context,
        array $data = []
    ) {
    
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * Initialize Storelocator edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'location_id';
        $this->_blockGroup = 'Yosto_Storepickup';
        $this->_controller = 'adminhtml_location';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Save Location'));
        $this->buttonList->add(
            'save-and-continue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => [
                            'event' => 'saveAndContinueEdit',
                            'target' => '#edit_form'
                        ]
                    ]
                ]
            ],
            -100
        );
        $this->buttonList->update('delete', 'label', __('Delete Location'));
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        /** @var \Yosto\Storepickup\Model\Location $location */
        $location = $this->coreRegistry->registry('yosto_storepickup_location');
        if ($location->getId()) {
            return __("Edit location '%1'", $this->escapeHtml($location->getData('store_title')));
        }
        return __('New Location');
    }
}
