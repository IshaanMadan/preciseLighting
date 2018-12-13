<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Model\Module;

class DetailsData extends \MageWorkshop\Core\Model\Module\AbstractDetailsData
{
    const MODULE_CODE = 'MageWorkshop_CustomerPermissions';

    /** @var string $publicName */
    protected $publicName = 'DR_Customer Permissions';

    /** @var bool $isPaid */
    protected $isPaid = true;
}