<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Plugin\Review\Model;

use Magento\Review\Model\Review;

class ReviewPlugin extends \MageWorkshop\DetailedReview\Plugin\Review\AbstractReview
{
    /**
     * @param Review $review
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function beforeSave(Review $review)
    {
        $validate = $this->checkValidation($review);
        if (is_array($validate) && !empty($validate)) {
            foreach ($validate as $errorMessage) {
                $this->messageManager->addErrorMessage($errorMessage);
            }
        }
    }
}
