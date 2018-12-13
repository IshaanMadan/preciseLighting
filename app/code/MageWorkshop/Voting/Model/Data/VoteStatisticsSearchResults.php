<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model\Data;

use MageWorkshop\Voting\Model\Data\ReviewVotesStatistics;

class VoteStatisticsSearchResults implements \MageWorkshop\Voting\Api\Data\VoteSearchResultsInterface
{
    /**
     * @var ReviewVotesStatistics[]
     */
    protected $items = [];
    /**
     * Get vote items list.
     *
     * @return ReviewVotesStatistics[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set set vote items list.
     *
     * @param ReviewVotesStatistics[] $items
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;
    }
}