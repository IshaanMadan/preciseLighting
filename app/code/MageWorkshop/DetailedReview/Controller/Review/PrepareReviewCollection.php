<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Review;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\View\Result\Layout;

class PrepareReviewCollection extends \Magento\Review\Controller\Product
{
    /**
     * Dispatch request
     *
     * @return ResultInterface
     * @throws \InvalidArgumentException
     * @throws NotFoundException
     */
    public function execute()
    {
        $this->initProduct();
        /** @var Layout $resultLayout */
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        return $resultLayout;
    }
}
