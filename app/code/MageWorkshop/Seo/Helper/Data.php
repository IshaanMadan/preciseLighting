<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Seo\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const XML_PATH_MAGEWORKSHOP_SEO_HIDE_REVIEW_BY = 'mageworkshop_detailedreview/mageworkshop_seo/hide_review_by';
    /**
     * @return string
     */
    public function getHideStyle()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_MAGEWORKSHOP_SEO_HIDE_REVIEW_BY);
    }
}