<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Form;

use MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractForm;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = \MageWorkshop\DetailedReview\Model\Module\DetailsData::MODULE_CODE;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'form_id';
        $this->_controller = 'adminhtml_form';

        parent::_construct();

        $this->addButton(
            'save_and_edit_button',
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
            ]
        );

        $this->buttonList->update('save', 'label', __('Save Form'));
        $this->buttonList->update('save', 'class', 'save primary');
        $this->buttonList->update(
            'save',
            'data_attribute',
            [
                'mage-init' =>
                    [
                        'button' =>
                            [
                                'event' => 'save',
                                'target' => '#edit_form'
                            ]
                    ]
            ]
        );

        if (!$reviewForm = $this->coreRegistry->registry(AbstractForm::REGISTRY_KEY)) {
            $this->buttonList->remove('delete');
        } else {
            $this->buttonList->update('delete', 'label', __('Delete Form'));
            $message = __('Are you sure you want to do this? Review form will be deleted and parent/default form will be used instead.');
            $this->buttonList->update('delete', 'onclick', "deleteConfirm('$message', '{$this->getDeleteUrl()}')");
        }
    }

    /**
     * Retrieve URL for validation
     *
     * @return string
     */
    public function getValidationUrl()
    {
        return $this->getUrl('*/*/validate', ['_current' => true]);
    }

    /**
     * Retrieve URL for save
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true, 'back' => null]
        );
    }
}
