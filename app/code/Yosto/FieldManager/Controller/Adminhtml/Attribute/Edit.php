<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Controller\Adminhtml\Attribute;

use Yosto\FieldManager\Controller\Adminhtml\AbstractController;

/**
 * Class Edit
 * @package Yosto\FieldManager\Controller\Adminhtml\Attribute
 */
class Edit extends AbstractController
{
    /**
     * @return $this|\Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('attribute_id');
        if ($id) {
            $model = $this->_attributeFactory->create()->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This attribute no longer exists.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('yosto_opc_fm/*/');
            }

        }

        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getAttributeData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $attributeData = $this->getRequest()->getParam('attribute');
        if (!empty($attributeData) && $id === null) {
            $model->addData($attributeData);
        }


        $this->_coreRegistry->register('address_entity_attribute', $model);

        $item = $id ? __('Edit Address Attribute') : __('New Address Attribute');

        $resultPage = $this->createActionPage($item);
        $resultPage->getConfig()->getTitle()->prepend($id ? $model->getName() : __('New Address Attribute'));
        return $resultPage;
    }
}