<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Block\Check;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Session\SessionManager;
use Magento\Customer\Model\Session;

class Rules extends Template
{
    /** @var UrlInterface $urlInterface */
    protected $urlInterface;

    /** @var SessionManager $sessionManager */
    protected $sessionManager;

    /** @var Session $customerSession */
    protected $customerSession;

    /**
     * Rules constructor.
     * @param SessionManager $sessionManager
     * @param Session $customerSession
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionManager $sessionManager,
        Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->urlInterface = $context->getUrlBuilder();
        $this->sessionManager   = $sessionManager;
        $this->customerSession  = $customerSession;
    }

    /**
     * @return string
     */
    public function getPermissionsCheckUrl()
    {
        return $this->urlInterface->getUrl('mageworkshop_customerpermissions/check/rules');
    }
}