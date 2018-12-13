<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Attribute;

class Delete extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractAttribute
{
    /**
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id = $this->getRequest()->getParam('attribute_id')) {
            /** @var \MageWorkshop\DetailedReview\Model\Attribute $attribute */
            $attribute = $this->attributeFactory->create();

            // entity type check
            $attribute->load($id);
            if ($attribute->getEntityTypeId() != $this->getEntityTypeId()) {
                $this->messageManager->addError(__('This is not a review attribute. It can not be deleted.'));
                return $resultRedirect->setPath('*/*/');
            }

            try {
                $attribute->delete();
                $this->messageManager->addSuccess(__('Review attribute was successfully deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath(
                    '*/*/edit',
                    ['attribute_id' => $this->getRequest()->getParam('attribute_id')]
                );
            }
        }
        $this->messageManager->addError(__(self::ATTRIBUTE_NO_LONGER_EXISTS_EXCEPTION));
        return $resultRedirect->setPath('*/*/');
    }
}
