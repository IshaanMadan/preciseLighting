<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Observer\Review;

use Magento\Framework\Event\Observer;
use Magento\Review\Model\Review;

class SaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    const REGISTRY_KEY_REVIEW_APPROVED_AUTOMATICALLY = 'mageworkshop_customerpermissions_review_approved_automatically';

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \MageWorkshop\CustomerPermissions\Helper\VerifiedHelper $verifiedHelper
     */
    private $verifiedHelper;

    /**
     * SaveBefore constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \MageWorkshop\CustomerPermissions\Helper\VerifiedHelper $verifiedHelper
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \MageWorkshop\CustomerPermissions\Helper\VerifiedHelper $verifiedHelper
    ) {
        $this->verifiedHelper  = $verifiedHelper;
        $this->registry = $registry;
    }

    /**
     * @param Observer $observer
     * @throws \Exception
     */
    public function execute(Observer $observer)
    {
        if ($this->verifiedHelper->isAutoApproveEnabled()) {
            $review = $observer->getEvent()->getData('object');

            if ($this->verifiedHelper->isAutoApproveAvailable()
                && ($review->getStatusId() === Review::STATUS_APPROVED)
                && !$this->registry->registry(self::REGISTRY_KEY_REVIEW_APPROVED_AUTOMATICALLY)
            ) {
                $this->registry->register(self::REGISTRY_KEY_REVIEW_APPROVED_AUTOMATICALLY, true);
            }
        }
    }
}
