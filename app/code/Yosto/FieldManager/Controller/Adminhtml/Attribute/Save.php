<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\FieldManager\Controller\Adminhtml\Attribute;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\AttributeRepository;
use Magento\Framework\Controller\Result;
use Magento\Framework\View\Result\PageFactory;
use Yosto\FieldManager\Model\EavAttributeFactory;
use Yosto\FieldManager\Model\ResourceModel\EavAttribute\CollectionFactory;
use Magento\Ui\Component\MassAction\Filter;
use Yosto\FieldManager\Controller\Adminhtml\AbstractController;
use Magento\Eav\Model\Entity\AttributeFactory;
/**
 * Save customera and address attribute
 *
 * Class Save
 * @package Yosto\FieldManager\Controller\Adminhtml\Attribute
 */
class Save extends AbstractController
{
    /**
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {

            $redirectBack = $this->getRequest()->getParam('back', false);
            $model = $this->_attributeFactory->create();

            $attributeId = $this->getRequest()->getParam('attribute_id');
            if ($attributeId) {
                $model->load($attributeId);
                $model->addData($data);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This attribute no longer exists.'));
                    return $resultRedirect->setPath('yosto_opc_fm/*/');
                } else {

                    try {
                        $model->save();


                        $this->messageManager->addSuccessMessage(__('You saved the address attribute.'));
                        $this->_cache->invalidate(['full_page', 'eav']);
                        $this->_session->setAttributeData(false);

                        if ($redirectBack) {
                            $resultRedirect->setPath('yosto_opc_fm/*/edit', ['attribute_id' => $model->getId(), '_current' => true]);
                        } else {
                            $resultRedirect->setPath('yosto_opc_fm/*/');
                        }
                        return $resultRedirect;
                    } catch (\Exception $e) {
                        $this->messageManager->addErrorMessage($e->getMessage());
                        $this->_session->setAttributeData($data);
                        return $resultRedirect->setPath('yosto_opc_fm/*/edit', ['attribute_id' => $attributeId, '_current' => true]);
                    }
                }
            }


        }
        return $resultRedirect->setPath('yosto_opc_fm/*/');
    }
}