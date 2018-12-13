<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Api\Data;

interface VoteStatisticsSearchResultsInterface
{
    /**
     * Get vote items list.
     *
     * @return \MageWorkshop\Voting\Model\Data\ReviewVotesStatistics[]
     */
    public function getItems();

    /**
     * Set set vote items list.
     *
     * @param \MageWorkshop\Voting\Model\Data\ReviewVotesStatistics[] $items
     * @return $this
     */
    public function setItems(array $items);
}