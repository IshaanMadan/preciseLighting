<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model;

use MageWorkshop\Voting\Model\ResourceModel\Vote as VoteResource;

/**
 * Class Vote
 * @package MageWorkshop\Voting\Model
 *
 * @method int getVoteId()
 * @method $this setVoteId(int $id)
 * @method int getCustomerId()
 * @method $this setCustomerId(int $customerId)
 * @method int getGuestToken()
 * @method $this setGuestToken(string $guestToken)
 * @method int getProductId()
 * @method $this setProductId(int $productId)
 * @method int getReviewId()
 * @method $this setReviewId(int $reviewId)
 * @method int getVote()
 * @method $this setVote(int $reviewId)
 */
class Vote extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Table name for votes table
     */
    const TABLE_NAME = 'mageworkshop_voting_vote';

    /**
     * constants for database field names
     */
    const KEY_VOTE_ID       = 'vote_id';
    const KEY_REVIEW_ID     = 'review_id';
    const KEY_CUSTOMER_ID   = 'customer_id';
    const KEY_GUEST_TOKEN   = 'guest_token';
    const KEY_PRODUCT_ID    = 'product_id';
    const KEY_VOTE          = 'vote';


    protected function _construct()
    {
        $this->_init(VoteResource::class);
        parent::_construct();
    }
}
