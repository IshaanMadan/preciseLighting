<?php

namespace Doyenhub\Preciselighting\Controller\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;

class CreateAccount extends \Magento\Framework\App\Action\Action
{

	  /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var \MagePal\GmailSmtpApp\Helper\Data
     */
    protected $_dataHelper;
    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;
    /**
     * @var \Magento\Sales\Api\OrderCustomerManagementInterface
     */
    protected $orderCustomerService;
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var \MagePal\GuestToCustomer\Helper\Data
     */
    protected $helperData;

    protected $_customerSession;

    protected $customer;

    protected $_storeManager;
    protected $resultRedirect;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Customer\Model\CustomerFactory    $customerFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Sales\Api\OrderCustomerManagementInterface $orderCustomerService,
        //\MagePal\GuestToCustomer\Helper\Data $helperData,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Controller\ResultFactory $result
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->orderRepository = $orderRepository;
        $this->orderCustomerService = $orderCustomerService;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->accountManagement = $accountManagement;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->customer = $customer;
        $this->resultRedirect = $result;
    }

    public function execute()
    {

        $request = $this->getRequest();

        $data=$request->getParams();

        $orderId = $data['orderid'];
        $pwd = $data['password'];    

        $resultRedirect = $this->resultRedirect->create(ResultFactory::TYPE_REDIRECT);

          if ($orderId) {
                /** @var  $order \Magento\Sales\Api\Data\OrderInterface */
                $order = $this->orderRepository->get($orderId);
                if ($order->getEntityId() && $this->accountManagement->isEmailAvailable($order->getEmailAddress())) {
                    try {
                        $this->_customerSession->setGuestToCustomer('1');
                        $this->orderCustomerService->create($orderId);

                        $customerEmail = $order->getCustomerEmail();

                        $websiteId=$this->_storeManager->getStore()->getWebsiteId();

                        $this->customer->setWebsiteId($websiteId);
                        $this->customer->loadByEmail($customerEmail);
                        $this->customer->setPassword($pwd);
                        $this->customer->save();

                        $this->messageManager->addSuccessMessage(__('Customer successfully created.'));                        
                        
                        return $resultRedirect->setPath('customer/account/');

                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage());

                        return $resultRedirect->setPath('customer/account/');
                    }
                } else {
                  
                    $this->messageManager->addErrorMessage('Email address already belong to an existing customer.');

                    return $resultRedirect->setPath('customer/account/');
                }
            } else {

                $this->messageManager->addErrorMessage('Invalid order id.');

                return $resultRedirect->setPath('customer/account/');
            }
        
    }
}