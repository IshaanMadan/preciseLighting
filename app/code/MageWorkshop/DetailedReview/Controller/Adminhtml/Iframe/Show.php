<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Iframe;

/**
 * Class to show swatch image and save it on disk
 */
class Show extends \Magento\Swatches\Controller\Adminhtml\Iframe\Show
{
    const RESOURCE = 'mageworkshop_detailedreview::review_attribute';

    /**
     * Check if user has enough privileges
     *
     * @codeCoverageIgnore
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RESOURCE);
    }
}
