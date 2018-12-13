<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Storepickup\Controller\Adminhtml\Import;

class Index extends \Yosto\Storepickup\Controller\Adminhtml\Import
{

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Yosto_Storepickup::import');
        $resultPage->getConfig()->getTitle()->prepend(__('Import'));
        $resultPage->addBreadcrumb(__('Yosto'), __('Yosto'));
        $resultPage->addBreadcrumb(__('Import'), __('Import'));
        return $resultPage;
    }
}
