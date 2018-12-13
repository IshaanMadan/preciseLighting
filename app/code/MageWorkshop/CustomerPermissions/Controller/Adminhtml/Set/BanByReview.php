<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Controller\Adminhtml\Set;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Review\Model\ReviewFactory;
use MageWorkshop\CustomerPermissions\Helper\BanHelper;

class BanByReview extends Action
{
    /** @var BanHelper $banHelper */
    protected $banHelper;

    /** @var ReviewFactory $reviewFactory */
    protected $reviewFactory;

    /**
     * BanByReview constructor.
     * @param BanHelper $banHelper
     * @param Context $context
     * @param ReviewFactory $reviewFactory
     */
    public function __construct(
        BanHelper $banHelper,
        Context $context,
        ReviewFactory $reviewFactory
    ) {
        $this->banHelper = $banHelper;
        $this->reviewFactory = $reviewFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $reviewsIds = $this->getRequest()->getParam('reviews');
        $banPeriod  = $this->getRequest()->getParam('ban');
        if (!is_array($reviewsIds) || !$banPeriod && $banPeriod != 0) {
            $this->messageManager->addError(__('Please select review(s).'));
        } else {
            $reviewCollection = $this->reviewFactory->create()
                ->getCollection();
            $reviewCollection->addFieldToFilter('main_table.review_id', ['in' => $reviewsIds]);
            try {
                $this->banHelper->banByReviews($reviewCollection, $banPeriod);
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('review/product/index');
        return $resultRedirect;
    }

}

