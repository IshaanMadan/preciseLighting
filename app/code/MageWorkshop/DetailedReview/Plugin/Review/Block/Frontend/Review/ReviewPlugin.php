<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Plugin\Review\Block\Frontend\Review;

use Magento\Review\Model\Review;

class ReviewPlugin extends \MageWorkshop\DetailedReview\Plugin\Review\AbstractReview
{
    /**
     * @param Review $review
     * @param array $errors
     * @return array
     */
    public function afterValidate(Review $review, $errors)
    {
        if ($this->dataHelper->isModuleEnabled(\MageWorkshop\DetailedReview\Model\Module\DetailsData::MODULE_CODE)) {
            return $this->checkValidation($review, $errors);
        }

        return $errors;
    }
}
