<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Api\Data;

interface VoteSearchResultsInterface
{
    /**
     * Get vote items list.
     *
     * @return \MageWorkshop\Voting\Model\Vote[]
     */
    public function getItems();

    /**
     * Set set vote items list.
     *
     * @param \MageWorkshop\Voting\Model\Vote[] $items
     * @return $this
     */
    public function setItems(array $items);
}