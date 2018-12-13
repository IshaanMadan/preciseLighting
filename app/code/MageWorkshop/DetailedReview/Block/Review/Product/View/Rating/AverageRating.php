<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review\Product\View\Rating;

use Magento\Review\Model\Rating\Option\Vote;
use Magento\Review\Model\Review;

class AverageRating extends \Magento\Review\Block\Product\View\ListView
{
    /** Maximum number of stars for rating */
    const QUANTITY_STARTS = 5;

    const BEST_RATING = 100;

    // need refactoring
    /**
     * @return array
     */
    public function getAverageRating()
    {
        $averageRating = [];
        /** @var Review $review */
        foreach ($this->getReviewsCollection()->getItems() as $review) {
            /** @var Vote $vote */
            foreach ($review->getRatingVotes() as $vote) {
                if (array_key_exists($vote->getRatingCode(), $averageRating)) {
                    if (isset($averageRating[$vote->getRatingCode()])) {
                        $valueRating = round((($averageRating[$vote->getRatingCode()]['value'] + (int)$vote->getValue()) / 2), 2);
                        $averageRating[$vote->getRatingCode()]['value'] = $valueRating;
                        $averageRating[$vote->getRatingCode()]['percent'] = $valueRating * self::BEST_RATING / self::QUANTITY_STARTS;
                    }
                } else {
                    $averageRating[$vote->getRatingCode()]['value'] = $vote->getValue();
                    $averageRating[$vote->getRatingCode()]['percent'] = $vote->getValue() * self::BEST_RATING / self::QUANTITY_STARTS;
                }
            }
        }
        return $averageRating;
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
