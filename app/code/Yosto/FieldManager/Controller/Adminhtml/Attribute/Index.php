<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class Index
 * @package Yosto\FieldManager\Controller\Adminhtml\Attribute
 */
class Index extends Action
{

    protected $_logger;
    protected $_resultPageFactory;
    protected $_eavAttributeFactory;
    protected $_coreRegistry;
    protected $_eavCollectionFactory;
    protected $_filter;
    protected $_relationFactory;
    protected $_relationValueFactory;
    public function __construct(
        Action\Context $context,
        PageFactory $pageFactory,
        Filter $filter,
        Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $pageFactory;
        $this->_filter = $filter;
        $this->_coreRegistry = $coreRegistry;
    }
    public function execute()
    {
        if ($this->getRequest()->getQuery('ajax')) {
            $this->_forward('grid');
            return;
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Yosto_FieldManager::address_attributes');
        $resultPage->getConfig()
            ->getTitle()
            ->prepend(__('Fields'));

        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization
            ->isAllowed('Yosto_Opc::field_manager');
    }

}