<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml\Location;

/**
 * Class Delete
 * @package Yosto\Storepickup\Controller\Adminhtml\Location
 */
class Delete extends \Yosto\Storepickup\Controller\Adminhtml\Location
{
    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id = $this->getRequest()->getParam('location_id');
        if ($id) {
            $store_title = "";
            try {
                $location = $this->locationFactory->create();
                $location->load($id);
                $storeTitle = $location->getData('store_title');
                $location->delete();
                $this->messageManager->addSuccessMessage(__('The location has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_yosto_storepickup_location_on_delete',
                    ['store_title' => $store_title, 'status' => 'success']
                );
                $resultRedirect->setPath('yosto_storepickup/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_yosto_storepickup_location_on_delete',
                    ['store_title' => $store_title, 'status' => 'fail']
                );
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                $resultRedirect->setPath('yosto_storepickup/*/edit', ['location_id' => $id]);
                return $resultRedirect;
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('Location to delete was not found.'));
        // go to grid
        $resultRedirect->setPath('yosto_storepickup/*/');
        return $resultRedirect;
    }
}
