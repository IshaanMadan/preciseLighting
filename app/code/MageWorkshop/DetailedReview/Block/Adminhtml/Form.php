<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

class Form extends Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_form';
        $this->_blockGroup = \MageWorkshop\DetailedReview\Model\Module\DetailsData::MODULE_CODE;
        $this->_headerText = __('Review Forms');
        $this->_addButtonLabel = __('Add New Review Form');
        parent::_construct();
    }
}
