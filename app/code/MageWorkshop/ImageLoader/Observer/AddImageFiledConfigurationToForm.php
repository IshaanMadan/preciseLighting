<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\ImageLoader\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Review\Model\Review;

class AddImageFiledConfigurationToForm implements ObserverInterface
{
    /**
     * @var \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper
     */
    private $imageLoaderHelper;

    /**
     * @var \MageWorkshop\ImageLoader\Helper\Media $mediaHelper
     */
    private $mediaHelper;

    /**
     * ReviewImagePlugin constructor.
     * @param \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper
     * @param \MageWorkshop\ImageLoader\Helper\Media $mediaHelper
     */
    public function __construct(
        \MageWorkshop\ImageLoader\Helper\Data $imageLoaderHelper,
        \MageWorkshop\ImageLoader\Helper\Media $mediaHelper
    ) {
        $this->imageLoaderHelper = $imageLoaderHelper;
        $this->mediaHelper = $mediaHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->imageLoaderHelper->isImageLoaderEnabled()) {
            return;
        }

        $transportObject = $observer->getEvent()->getData('transportObject');
        $attribute = $transportObject->getData('attribute');

        if ($attribute['frontend_input'] !== 'image') {
            return;
        }

        $attributeCode = $attribute['attribute_code'];
        $transportObject->setData('inputType', 'file');
        $transportObject->setData('config', array_merge(
            $transportObject->getData('config'),
            [
                'multiple' => 'multiple',
                'name' => $attributeCode . '[]'
            ]
        ));

        $transportObject->setData('validation', $attribute['validation_class']);

        /** @var Review $review */
        if ($review = $transportObject->getReview()) {
            $images = trim(trim($review->getData($attributeCode), ','));
            $images = array_filter(explode(',', $images));

            foreach ($images as $index => $fileName) {
                $images[$index] = $this->mediaHelper->getTmpMediaFullPathToImages(trim($fileName));
            }

            $review[$attributeCode] = implode(',', $images);
        }
    }
}
