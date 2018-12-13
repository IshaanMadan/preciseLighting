<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review\Product\View\Rating;

use Magento\Review\Model\Rating\Option\Vote;
use Magento\Review\Model\Review;

class CustomerRating extends \Magento\Review\Block\Product\View\ListView
{
    /** Maximum number of stars for rating */
    const QUANTITY_STARTS = 5;

    const BEST_RATING = 100;

    /**
     * @return array
     */
    public function getCustomerRating()
    {
        $reviewAverageRating = [];
        $sumVote = 0;
        /** @var Review $review */
        foreach ($this->getReviewsCollection()->getItems() as $review) {
            /** @var Vote $vote */
            foreach ($review->getRatingVotes() as $vote) {
                $sumVote += $vote->getValue();
            }
            $countRatingVotes = count($review->getRatingVotes());
            if (!$countRatingVotes) {
                continue;
            }
            $reviewAverageRating[] = (string)round($sumVote / $countRatingVotes);
            $sumVote = 0;
        }

        $sortedData = [];
        $data = array_count_values($reviewAverageRating);
        $countReviews = array_sum($data);

        for ($i = self::QUANTITY_STARTS; $i >= 1 ; $i--) {
            $sortedData[$i] = [
                'quantity' => isset($data[$i])
                    ? $data[$i]
                    : 0,
                'percent'  => $countReviews && isset($data[$i])
                    ? ($data[$i] / $countReviews) * self::BEST_RATING
                    : 0
            ];
        }

        return $sortedData;
    }

    /**
     * Don't render this block if no reviews id present
     * @return string
     */
    public function _toHtml()
    {
        $html = '';
        $product = $this->getProduct();

        if ($product && $this->getReviewsCollection()->getSize()) {
            $html = parent::_toHtml();
        }

        return $html;
    }
}
