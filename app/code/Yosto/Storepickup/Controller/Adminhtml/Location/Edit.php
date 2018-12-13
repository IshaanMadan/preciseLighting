<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml\Location;

/**
 * Class Edit
 * @package Yosto\Storepickup\Controller\Adminhtml\Location
 */
class Edit extends \Yosto\Storepickup\Controller\Adminhtml\Location
{
    /**
     * Backend session
     *
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * Page factory
     *
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Result JSON factory
     *
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;


    public function __construct(
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Yosto\Storepickup\Model\LocationFactory $locationFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
    
        $this->backendSession    = $context->getSession();
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($locationFactory, $registry, $context);
    }

    /**
     * is action allowed
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Yosto_Opc::storepickup');
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('location_id');
        $location = $this->initLocation();
        /** @var \Magento\Backend\Model\View\Result\Page|\Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Yosto_Storepickup::location');
        $resultPage->getConfig()->getTitle()->set(__('Location'));
        if ($id) {
            $location->load($id);
            if (!$location->getId()) {
                $this->messageManager->addErrorMessage(__('This Storelocator no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath(
                    'yosto_storepickup/*/edit',
                    [
                        'location_id' => $location->getId(),
                        '_current' => true
                    ]
                );
                return $resultRedirect;
            }
        }
        $title = $location->getId() ? $location->getStore_title() : __('New Location');
        $resultPage->getConfig()->getTitle()->prepend($title);
        $data = $this->backendSession->getData('storepickup_location_data', true);
        if (!empty($data)) {
            $location->setData($data);
        }
        return $resultPage;
    }
}
