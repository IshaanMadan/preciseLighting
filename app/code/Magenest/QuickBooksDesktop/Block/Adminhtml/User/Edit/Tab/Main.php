<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Block\Adminhtml\User\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Main
 *
 * @package Magenest\QuickBooksDesktop\Block\Adminhtml\User\Edit\Tab
 */
class Main extends Generic implements TabInterface
{
    protected $_prepareForm;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Magento\Customer\Api\GroupRepositoryInterface
     */
    protected $_groupRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $_searchCriteriaBuilder;
    /**
     * @var \Magento\Framework\Convert\DataObject
     */
    protected $_objectConverter;

    /**
     * @var \Magenest\QuickBooksDesktop\Model\Status
     */
    protected $_status;

    /**
     * @var
     */
    protected $_fieldFactory;

    /**
     * Main constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Customer\Api\GroupRepositoryInterface $groupRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     * @param \Magento\Framework\Convert\DataObject $objectConverter
     * @param \Magenest\QuickBooksDesktop\Model\Status $status
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Customer\Api\GroupRepositoryInterface $groupRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Convert\DataObject $objectConverter,
        \Magenest\QuickBooksDesktop\Model\Status $status,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->_systemStore = $systemStore;
        $this->_groupRepository = $groupRepository;
        $this->_searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->_objectConverter = $objectConverter;
        $this->_status = $status;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * prepare form
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareForm()
    {
        /* @var $model \Magenest\QuickbooksDesktop\Model\User */
        $model = $this->_coreRegistry->registry('user');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('User Information')]);

        if ($model->getId()) {
            $fieldset->addField(
                'user_id',
                'hidden',
                [
                    'name' =>'user_id'
                ]
            );
        }

        $fieldset->addField(
            'username',
            'text',
            [
                'name' => 'username',
                'label' => __('User Name'),
                'title' => __('User Name'),
                'required' => true,
            ]
        );
        $isNew = $model->isObjectNew();
        if ($isNew) {
            $passwordLabel = __('Password');
        } else {
            $passwordLabel = __('New Password');
        }
        $confirmationLabel = __('Password Confirmation');

        /**   */
        $this->_addPassword($fieldset, $passwordLabel, $confirmationLabel, $isNew);
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'status',
                'required' => true,
                'options' => $this->_status->getOptionArray(),
            ]
        );
        $dateFormat = $this->_localeDate->getDateFormat(
            \IntlDateFormatter::SHORT
        );
        $fieldset->addField(
            'expired_date',
            'date',
            [
                'label' => __('Expired Date'),
                'title' => __('Expired Date'),
                'name' => 'expired_date',
                'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'date_format' => $dateFormat
            ]
        );
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\Fieldset $fieldset
     * @param $passwordLabel
     * @param $confirmationLabel
     * @param bool $isRequired
     */
    protected function _addPassword(
        \Magento\Framework\Data\Form\Element\Fieldset $fieldset,
        $passwordLabel,
        $confirmationLabel,
        $isRequired = false
    ) {
        $requiredFieldClass = $isRequired ? ' required-entry' : '';
        $fieldset->addField(
            'password_form',
            'password',
            [
                'name' => 'password',
                'label' => $passwordLabel,
                'id' => 'customer_pass',
                'title' => $passwordLabel,
                'class' => 'input-text validate-admin-password' . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
        $fieldset->addField(
            'confirmation',
            'password',
            [
                'name' => 'password_confirmation',
                'label' => $confirmationLabel,
                'id' => 'confirmation',
                'title' => $confirmationLabel,
                'class' => 'input-text validate-cpassword' . $requiredFieldClass,
                'required' => $isRequired
            ]
        );
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Information');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('General Information');
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
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
