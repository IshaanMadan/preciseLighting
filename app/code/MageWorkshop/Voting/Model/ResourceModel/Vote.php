<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model\ResourceModel;

use MageWorkshop\Voting\Model\Vote as VoteModel;

class Vote extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init(VoteModel::TABLE_NAME, 'vote_id');
    }
}
