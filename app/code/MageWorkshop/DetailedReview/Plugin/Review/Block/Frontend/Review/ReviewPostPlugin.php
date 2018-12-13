<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Plugin\Review\Block\Frontend\Review;

use Magento\Framework\Controller\Result\Redirect;
use Magento\Review\Controller\Product\Post;

class ReviewPostPlugin
{
    const PRODUCT_REVIEW_TAB_ID = '#reviews';

    /** @var \Magento\Framework\App\Response\RedirectInterface $redirect */
    private $redirect;

    /**
     * ReviewPostPlugin constructor.
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     */
    public function __construct(\Magento\Framework\App\Response\RedirectInterface $redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * @param Post $review
     * @param Redirect $resultRedirect
     * @return Redirect $resultRedirect
     */
    public function afterExecute(Post $review, $resultRedirect)
    {
        return $resultRedirect->setUrl($this->redirect->getRedirectUrl() . self::PRODUCT_REVIEW_TAB_ID);
    }
}