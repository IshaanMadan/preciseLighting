<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Form;

class Index extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractForm
{
    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->createActionPage();
        // We may want to use this approach instead of the layout if the functionality is moved into the EAV module
        // $resultPage->addContent(
        //     $resultPage->getLayout()->createBlock('MageWorkshop\DetailedReview\Block\Adminhtml\Attribute')
        // );
        return $resultPage;
    }
}
