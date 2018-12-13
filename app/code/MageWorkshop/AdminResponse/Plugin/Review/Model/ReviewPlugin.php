<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\AdminResponse\Plugin\Review\Model;

use Magento\Review\Model\Review;
use MageWorkshop\AdminResponse\Model\AdminResponse;

class ReviewPlugin
{
    /**
     * @var \MageWorkshop\AdminResponse\Model\AdminResponse $adminResponse
     */
    private $adminResponse;

    /**
     * @param \MageWorkshop\AdminResponse\Model\AdminResponse $adminResponse
     */
    public function __construct(
        \MageWorkshop\AdminResponse\Model\AdminResponse $adminResponse
    ) {
        $this->adminResponse = $adminResponse;
    }

    /**
     * @param Review $subject
     * @param Review $result
     * @return Review
     * @throws \Exception
     */
    public function afterAfterDeleteCommit(Review $subject, Review $result)
    {
        $adminResponse = $this->adminResponse->getAdminResponseByReview($subject);

        if ($subject->isDeleted() && $adminResponse->getId()) {
            $adminResponse->delete();
        }

        return $result;
    }
}
