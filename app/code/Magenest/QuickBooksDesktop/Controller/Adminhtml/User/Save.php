<?php
/**
 * Copyright © 2017 Magenest. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Magenest_QuickBooksDesktop extension
 * NOTICE OF LICENSE
 */
namespace Magenest\QuickBooksDesktop\Controller\Adminhtml\User;

use Magenest\QuickBooksDesktop\Controller\Adminhtml\User as AbstractUser;

/**
 * Class Save
 *
 * @package Magenest\QuickBooksDesktop\Controller\Adminhtml\User
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Save user
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $model = $this->_objectManager->create('Magenest\QuickBooksDesktop\Model\User');

            if (!empty($data['user_id'])) {
                $model->load($data['user_id']);
                if ($data['user_id'] != $model->getUserId()) {
                    throw new \Magento\Framework\Exception\LocalizedException(__('Wrong invoice rule.'));
                }
                $info = [
                    'username'     => $data['username'],
                    'status'       => $data['status'],
                    'expired_date' => $data['expired_date'],
                ];

                if (!empty($data['password'])) {
                    $info['password'] = md5($data['password']);
                }
            } else {
                $info = [
                         'username'     => $data['username'],
                         'password'     => md5($data['password']),
                         'status'       => $data['status'],
                         'expired_date' => $data['expired_date'],
                        ];
            }

            $model->addData($info);
            $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($model->getData());
            try {
                $model->save();

                $this->messageManager->addSuccessMessage(__('User has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e, __('Something went wrong while saving the user.'));
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
