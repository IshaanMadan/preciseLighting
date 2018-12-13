<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Controller\Check;

use Magento\Framework\Session\SessionManager;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Helper\Product;

class Rules extends Action
{

    /** @var PageFactory $resultPageFactory */
    protected $resultPageFactory;

    /** @var SessionManager $sessionManager */
    protected $sessionManager;

    /** @var Product $productHelper */
    protected $productHelper;

    /**
     * Rules constructor.
     * @param SessionManager $sessionManager
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Product $productHelper
     */
    public function __construct(
        SessionManager $sessionManager,
        Context $context,
        PageFactory $resultPageFactory,
        Product $productHelper
    ) {
        $this->sessionManager    = $sessionManager;
        $this->resultPageFactory = $resultPageFactory;
        $this->productHelper     = $productHelper;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($productId = $this->getRequest()->getParam('productId')) {
            $this->productHelper->initProduct($productId, $this);
        }
        if ($email = $this->getRequest()->getParam('verify-email')) {
            $this->sessionManager->setData('customer_email', $email);
        }
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }
}