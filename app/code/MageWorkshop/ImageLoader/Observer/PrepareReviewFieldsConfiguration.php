<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\ImageLoader\Observer;

use Magento\Framework\Event\ObserverInterface;
use MageWorkshop\DetailedReview\Model\ResourceModel\Attribute\Collection;

class PrepareReviewFieldsConfiguration implements ObserverInterface
{
    /**
     * @var \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper
     */
    private $imageLoaderHelper;

    /**
     * ReviewImagePlugin constructor.
     * @param \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper
     */
    public function __construct(
        \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper
    ) {
        $this->imageLoaderHelper = $imageLoaderHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->imageLoaderHelper->isImageLoaderEnabled()) {
            /** @var Collection $reviewFormAttributes */
            $reviewFormAttributes = $observer->getEvent()->getData('review_form_attributes');
            $reviewFormAttributes->addFieldToFilter('frontend_input', ['neq' => 'image']);
        }
    }
}
