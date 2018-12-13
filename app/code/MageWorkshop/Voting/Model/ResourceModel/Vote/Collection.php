<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Voting\Model\ResourceModel\Vote;

use MageWorkshop\Voting\Model\Vote as VoteModel;
use MageWorkshop\Voting\Model\ResourceModel\Vote as VoteResource;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init(VoteModel::class, VoteResource::class);
    }
}
