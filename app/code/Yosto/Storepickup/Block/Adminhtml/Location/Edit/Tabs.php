<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Block\Adminhtml\Location\Edit;

/**
 * Class Tabs
 * @package Yosto\Storepickup\Block\Adminhtml\Location\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('location_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Location Information'));
    }
}
