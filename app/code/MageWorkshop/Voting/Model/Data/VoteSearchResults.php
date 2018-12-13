<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model\Data;

use MageWorkshop\Voting\Model\Exception\NotFoundException;
use MageWorkshop\Voting\Model\Vote as VoteModel;

class VoteSearchResults implements \MageWorkshop\Voting\Api\Data\VoteSearchResultsInterface
{
    const EXCEPTION_NO_FIRST_VOTE_ITEM = 'Error getting first vote item from the empty result object';

    /** @var VoteModel[] */
    protected $items = [];

    /**
     * Get vote items list.
     *
     * @return VoteModel[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set set vote items list.
     *
     * @param VoteModel[] $items
     * @return $this
     */
    public function setItems(array $items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return VoteModel
     * @throws NotFoundException
     */
    public function getFirstItem()
    {
        if (!empty($this->items)) {
            return array_values($this->items)[0];
        } else {
            throw new NotFoundException(self::EXCEPTION_NO_FIRST_VOTE_ITEM);
        }
    }
}