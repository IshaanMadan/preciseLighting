<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Plugin\Customer\Model;

use Magento\Customer\Model\Customer\DataProvider;
use MageWorkshop\CustomerPermissions\Model\Module\DetailsData;

class DataProviderPlugin
{
    /** 'banned_ill' field id - needs to be hidden if this module is switched off  */
    const BANNED_TILL_FIELD_ID = 'banned_till';

    /** @var \MageWorkshop\Core\Helper\Data */
    protected $coreHelper;

    /**
     * FormPlugin constructor.
     * @param \MageWorkshop\Core\Helper\Data $coreHelper
     */
    public function __construct(
        \MageWorkshop\Core\Helper\Data $coreHelper
    ) {
        $this->coreHelper = $coreHelper;
    }

    /**
     * If the 'Customer Permission' module is disabled - remove the 'banned till' field
     * from customers fields meta info.
     *
     * @param DataProvider $dataProvider
     * @param array $result
     * @return mixed
     */
    public function afterGetFieldsMetaInfo(DataProvider $dataProvider, $result)
    {
        if (
            !$this->coreHelper->isModuleEnabledInDetailedReviewSection(DetailsData::MODULE_CODE)
            && array_key_exists(self::BANNED_TILL_FIELD_ID, $result)
        ) {
            unset($result['banned_till']);
        }

        return $result;
    }
}