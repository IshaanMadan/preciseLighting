<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Model;

use Magento\Framework\Model\AbstractModel;
use MageWorkshop\CustomerPermissions\Model\ResourceModel\ReviewBan as ReviewBanResource;

class ReviewBan extends AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ReviewBanResource::class);
    }
}
