<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Block\Check;

use Magento\Framework\Session\SessionManager;
use Magento\Customer\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use MageWorkshop\CustomerPermissions\Helper\VerifiedHelper;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;

class Verified extends Rules
{
    /** @var VerifiedHelper $verifiedHelper */
    protected $verifiedHelper;

    /** @var Registry $registry */
    protected $registry;

    /**
     * Verified constructor.
     * @param SessionManager $sessionManager
     * @param Session $customerSession
     * @param Context $context
     * @param VerifiedHelper $verifiedHelper
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionManager $sessionManager,
        Session $customerSession,
        VerifiedHelper $verifiedHelper,
        Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $sessionManager, $customerSession, $data);
        $this->verifiedHelper = $verifiedHelper;
        $this->registry       = $registry;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $isValid = false;
        $product = $this->registry->registry('product');
        if ($product && $product->getId()) {
            $isValid = $this->verifiedHelper->allowToPostReviewForCurrentUser($product->getId());
        }
        return $isValid;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return !$this->isValid() ? parent::toHtml() : '';
    }

    /**
     * @return bool
     */
    public function customerHasData()
    {
        return $this->verifiedHelper->customerHasData();
    }
}
