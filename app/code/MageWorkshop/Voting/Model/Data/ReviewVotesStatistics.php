<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model\Data;


class ReviewVotesStatistics
{
    /**
     * @var int
     */
    protected $reviewId = 0;

    /**
     * @var int
     */
    protected $positivesCount = 0;

    /**
     * @var int
     */
    protected $negativesCount = 0;

    /**
     * @var int
     */
    protected $currentCustomerVote = 0;

    /**
     * @return int
     */
    public function getReviewId()
    {
        return (int) $this->reviewId;
    }

    /**
     * @param int $reviewId
     * @return $this
     */
    public function setReviewId($reviewId)
    {
        $this->reviewId = $reviewId;

        return $this;

    }

    /**
     * @return int
     */
    public function getPositivesCount()
    {
        return (int) $this->positivesCount;
    }

    /**
     * @param int $positivesCount
     * @return $this
     */
    public function setPositivesCount($positivesCount)
    {
        $this->positivesCount = $positivesCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getNegativesCount()
    {
        return $this->negativesCount;
    }

    /**
     * @param int $negativesCount
     * @return $this
     */
    public function setNegativesCount($negativesCount)
    {
        $this->negativesCount = $negativesCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getCurrentCustomerVote()
    {
        return (int) $this->currentCustomerVote;
    }

    /**
     * @param int $currentCustomerVote
     * @return $this
     */
    public function setCurrentCustomerVote($currentCustomerVote)
    {
        $this->currentCustomerVote = $currentCustomerVote;

        return $this;
    }
}