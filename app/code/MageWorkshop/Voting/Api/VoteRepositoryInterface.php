<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Api;

/**
 * @api
 */
interface VoteRepositoryInterface
{
    /**
     * @param \MageWorkshop\Voting\Model\Vote $vote
     * @return \MageWorkshop\Voting\Model\Vote
     */
    public function save(\MageWorkshop\Voting\Model\Vote $vote);

    /**
     * @param int $id
     * @return \MageWorkshop\Voting\Model\Vote
     */
    public function getById($id);

    /**
     * @param \MageWorkshop\Voting\Model\Vote $vote
     * @return bool
     */
    public function delete(\MageWorkshop\Voting\Model\Vote $vote);

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id);

    /**
     * Get vote objects list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageWorkshop\Voting\Api\Data\VoteSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}