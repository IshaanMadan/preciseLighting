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
use MageWorkshop\CustomerPermissions\Helper\BanHelper;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

class Banned extends Rules
{
    /** @var BanHelper $banHelper */
    protected $banHelper;

   /** @var CollectionFactory $collectionFactory */
    protected $collectionFactory;

    /**
     * Banned constructor.
     * @param SessionManager $sessionManager
     * @param Session $customerSession
     * @param Context $context
     * @param BanHelper $banHelper
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionManager $sessionManager,
        Session $customerSession,
        BanHelper $banHelper,
        CollectionFactory $collectionFactory,
        array $data
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->banHelper = $banHelper;
        parent::__construct($context, $sessionManager, $customerSession, $data);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->banHelper->customerHasData()
            ? $this->isAllowedToWriteReview()
            : true;
    }

    /**
     * @return string
     */
    public function toHtml()
    {
        return $this->isValid() ? '': parent::toHtml();
    }

    /**
     * @return bool
     */
    public function isAllowedToWriteReview()
    {
        $customer = $this->banHelper->getCustomerModel();
        return !$this->banHelper->isCustomerBanned($customer);
    }
}