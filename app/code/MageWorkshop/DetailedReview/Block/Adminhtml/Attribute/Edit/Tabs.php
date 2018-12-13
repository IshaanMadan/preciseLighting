<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Adminhtml\Attribute\Edit;

use Magento\Backend\Block\Widget\Tabs as TabsWidget;

/**
 * Class Tabs
 * @package MageWorkshop\DetailedReview\Block\Adminhtml\Attribute\Edit
 *
 * @method setTitle(string $title)
 * @method setId(string $id)
 */
class Tabs extends TabsWidget
{
    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('review_attribute_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Attribute Information'));
    }

    /**
     * @inheritdoc
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'main',
            [
                'label' => __('Properties'),
                'title' => __('Properties'),
                'content' => $this->getChildHtml('main'),
                'active' => true
            ]
        );
        $this->addTab(
            'labels',
            [
                'label' => __('Manage Labels'),
                'title' => __('Manage Labels'),
                'content' => $this->getChildHtml('labels')
            ]
        );

        return parent::_beforeToHtml();
    }
}
