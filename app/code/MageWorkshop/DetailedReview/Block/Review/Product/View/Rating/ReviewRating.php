<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review\Product\View\Rating;

class ReviewRating extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /** Maximum number of stars for rating */
    const QUANTITY_STARTS = 5;

    const BEST_RATING = 100;

    /** @var \Magento\Catalog\Model\Product */
    protected $product;

    /** @var \Magento\Review\Model\ReviewFactory */
    protected $reviewFactory;

    /** @var \MageWorkshop\DetailedReview\Helper\Review */
    protected $reviewHelper;

    /**
     * ReviewRating constructor.
     * @param \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \MageWorkshop\DetailedReview\Helper\Review $reviewHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Catalog\Block\Product\Context $context,
        \MageWorkshop\DetailedReview\Helper\Review $reviewHelper,
        array $data = []
    ) {
        $this->reviewFactory = $reviewFactory;
        $this->reviewHelper = $reviewHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return float|int
     */
    public function getAverageRating()
    {
        return $this->getRatingSummary() * self::QUANTITY_STARTS / self::BEST_RATING;
    }

    /**
     * @return mixed
     */
    protected function getRatingSummary()
    {
        $product = $this->getProduct();
        if (!$product->getRatingSummary()) {
            $this->reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
        }
        $this->setProduct($product);

        return $product->getRatingSummary()->getRatingSummary();
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     */
    public function setProduct(\Magento\Catalog\Model\Product $product)
    {
        $this->product = $product;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            $this->product = parent::getProduct();
        }

        return $this->product;
    }

    /**
     * Don't render this block if no reviews id present
     * @return string
     */
    public function _toHtml()
    {
        $html = '';
        $product = $this->getProduct();

        if ($product && $this->reviewHelper->getProductApprovedReviewsCount($product)) {
            $html = parent::_toHtml();
        }

        return $html;
    }
}
