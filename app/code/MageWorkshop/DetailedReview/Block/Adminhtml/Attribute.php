<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml;

class Attribute extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_attribute';
        $this->_blockGroup = \MageWorkshop\DetailedReview\Model\Module\DetailsData::MODULE_CODE;
        $this->_headerText = __('Review Fields');
        $this->_addButtonLabel = __('New Review Field');
        parent::_construct();
    }
}
