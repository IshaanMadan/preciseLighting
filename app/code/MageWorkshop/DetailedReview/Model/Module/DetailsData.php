<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Model\Module;

class DetailsData extends \MageWorkshop\Core\Model\Module\AbstractDetailsData
{
    const MODULE_CODE = 'MageWorkshop_DetailedReview';

    /** @var string $publicName */
    protected $publicName = 'Detailed Product Review';

    protected $isPaid = true;
}
