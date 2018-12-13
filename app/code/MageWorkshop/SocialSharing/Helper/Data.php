<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\SocialSharing\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * SocialSharing helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Config path for "Enable social sharing" adminhtml option
     */
    const XML_PATH_SOCIAL_SHARING_ENABLED = 'mageworkshop_detailedreview/socialsharing/enabled';

    /**
     * Returns true if the review social sharing is enabled in admin
     *
     * @return boolean
     */
    public function isSocialSharingEnabled()
    {
        return (bool) $this->scopeConfig->getValue(self::XML_PATH_SOCIAL_SHARING_ENABLED, ScopeInterface::SCOPE_STORE);
    }
}
