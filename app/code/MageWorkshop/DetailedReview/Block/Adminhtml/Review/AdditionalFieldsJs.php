<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Review;

class AdditionalFieldsJs extends \Magento\Backend\Block\Template
{
    /** @var \Magento\Framework\Registry $registry */
    protected $registry;

    /**
     * AdditionalFieldsJs constructor.
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
    }

    /**
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl(
            'mageworkshop_detailedreview/review/additionalFields',
            [
                'review_id' => $this->getRequest()->getParam('id')
            ]
        );
    }

    public function getProductId()
    {
        $productId = 0;
        /** @var \Magento\Review\Model\Review $reviewData */
        if ($reviewData = $this->registry->registry('review_data')) {
            // we do not need to load product and check if this is a product review -
            // this will be done by the FormPlugin while looking for the additional review fields
            $productId = $reviewData->getEntityPkValue();
        }
        return $productId;
    }
}
