<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Controller\Adminhtml\Attribute;

use MageWorkshop\DetailedReview\Model\Attribute;

class Edit extends \MageWorkshop\DetailedReview\Controller\Adminhtml\AbstractAttribute
{
    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $id = (int) $this->getRequest()->getParam('attribute_id');
        /** @var Attribute $attribute */
        $attribute = $this->attributeFactory->create();
        $attribute->setEntityTypeId($this->getEntityTypeId());

        if ($id) {
            $attribute->load($id);

            if (!$attribute->getId()) {
                $this->messageManager->addError(__(self::ATTRIBUTE_NO_LONGER_EXISTS_EXCEPTION));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }

            // entity type check
            if ($attribute->getEntityTypeId() != $this->getEntityTypeId()) {
                $this->messageManager->addError(__(self::INVALID_ENTITY_TYPE_EXCEPTION));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->attributeHelper->unpackValuesForVisualSettingsFields($attribute);
        $data = $this->_getSession()->getData('attribute_data', true);

        if (!empty($data)) {
            $attribute->addData($data);
        }
        $attributeData = $this->getRequest()->getParam('attribute');
        if (!empty($attributeData) && $id) {
            $attribute->addData($attributeData);
        }

        $this->coreRegistry->register('entity_attribute', $attribute);

        $item = $id ? __('Edit Review Field') : __('New Review Field');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $attribute->getName() : __('New Review Field'));
        return $resultPage;
    }
}
