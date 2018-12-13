<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Form;

class Delete extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractForm
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        // Default attribute set can not be deleted. This is validate in the _beforeSave method
        // of the attribute set model

        $resultRedirect = $this->resultRedirectFactory->create();
        $id = (int) $this->getRequest()->getParam('form_id');
        if ($attributeSet = $this->attributeHelper->getAttributeSet($id)) {
            if ($attributeSet->getEntityTypeId() != $this->getEntityTypeId()) {
                $this->messageManager->addError(__(self::INVALID_ENTITY_TYPE_EXCEPTION));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $attributeSet->delete();
                $this->messageManager->addSuccess(__('Review form was successfully deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['attribute_id' => $this->getRequest()->getParam('attribute_id')]
                );
            }
        }
        $this->messageManager->addError(__(self::FORM_NO_LONGER_EXISTS_EXCEPTION));
        return $resultRedirect->setPath('*/*/');
    }
}
