<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * customers defined options
 *
 * @author     Magento Core Team <core@magentocommerce.com>
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Widget;
use Magento\Backend\Block\Widget\Button;

class Validation extends Widget
{
    /**
     * @return Widget
     */
    protected function _prepareLayout()
    {
        $this->addChild(
            'add_button',
            Button::class,
            ['label' => __('Add New Option'), 'class' => 'add', 'id' => 'add_new_defined_option']
        );

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getAddButtonHtml()
    {
        return $this->getChildHtml('add_button');
    }

    /**
     * @return string
     */
    public function getOptionsBoxHtml()
    {
        return $this->getChildHtml('options_box');
    }
}
