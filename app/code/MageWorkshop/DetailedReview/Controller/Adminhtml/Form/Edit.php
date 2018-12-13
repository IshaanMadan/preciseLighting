<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Form;

class Edit extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractForm
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('form_id');
        if ($attributeSet = $this->attributeHelper->getAttributeSet($id)) {
            if (!$attributeSet->getId() || $attributeSet->getEntityTypeId() != $this->getEntityTypeId()) {
                $this->messageManager->addError(__(self::FORM_NO_LONGER_EXISTS_EXCEPTION));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $data = $this->_getSession()->getData(self::REGISTRY_KEY, true);
        if (!empty($data)) {
            $attributeSet->addData($data);
        }
        $attributeData = $this->getRequest()->getParam(self::REGISTRY_KEY);
        if (!empty($attributeData) && $id) {
            $attributeSet->addData($attributeData);
        }

        $this->coreRegistry->register(self::REGISTRY_KEY, $attributeSet);

        $item = $id ? __('Edit Review Form') : __('Add New Review Form');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend(
            $id ? $attributeSet->getAttributeSetName() : __('Add New Review Form')
        );
        return $resultPage;
    }
}
