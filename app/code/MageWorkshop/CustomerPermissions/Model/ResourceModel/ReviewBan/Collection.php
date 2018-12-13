<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Model\ResourceModel\ReviewBan;

use MageWorkshop\CustomerPermissions\Model\ReviewBan as ReviewBanModel;
use MageWorkshop\CustomerPermissions\Model\ResourceModel\ReviewBan as ReviewBanResource;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ReviewBanModel::class, ReviewBanResource::class);
    }
}
