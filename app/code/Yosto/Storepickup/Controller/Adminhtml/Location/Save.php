<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml\Location;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Yosto\Storepickup\Controller\Adminhtml\Location
{
    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $backendSession;

    /**
     * Save constructor.
     * @param \Yosto\Storepickup\Model\LocationFactory $storelocatorFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Yosto\Storepickup\Model\LocationFactory $storelocatorFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\App\Action\Context $context
    ) {
    
        $this->backendSession = $context->getSession();
        parent::__construct($storelocatorFactory, $registry, $context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Redirect|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('location');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            $location = $this->initLocation();
            $imageRequest = $this->getRequest()->getFiles('image');
            if ($imageRequest) {
                if (isset($imageRequest['name'])) {
                    $fileName = $imageRequest['name'];
                } else {
                    $fileName = '';
                }
            } else {
                 $fileName = '';
            }
                
            if ($imageRequest && strlen($fileName)) {
                try {
                        $uploader = $this->_objectManager->
                        create('Magento\MediaStorage\Model\File\Uploader', ['fileId' => 'image']);
                        $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                        $imageAdapter = $this->_objectManager
                        ->get('Magento\Framework\Image\AdapterFactory')->create();
                        $uploader->addValidateCallback('image', $imageAdapter, 'validateUploadFile');
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(true);
                        $mediaDirectory = $this->_objectManager->
                        get('Magento\Framework\Filesystem')->getDirectoryRead(DirectoryList::MEDIA);
                        $config = $this->_objectManager->get('Magento\Catalog\Model\Product\Media\Config');
                        $pth = $mediaDirectory->getAbsolutePath('Yosto/Storepickup/images');
                        $result = $uploader->save($mediaDirectory->getAbsolutePath('Yosto/Storepickup/images'));
                        unset($result['tmp_name']);
                        unset($result['path']);
                        $data['image'] = 'Yosto/Storepickup/images' . $result['file'];
                } catch (\Exception $e) {
                    $data['image'] = $fileName;
                }
            } elseif (isset($data['image']['delete'])) {
                if ($data['image']['value']) {
                    $om = \Magento\Framework\App\ObjectManager::getInstance();
                    $filesystem = $om->get('Magento\Framework\Filesystem');
                    $reader = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
                    $deletePath = $reader->getAbsolutePath($data['image']['value']);
                    
                    if (file_exists($deletePath)) {
                        unlink($deletePath);
                    }
                    $data['image'] = '';
                }
            } else {
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
            }

            $location->setData($data);
            $this->_eventManager->dispatch(
                'yosto_storepickup_location_prepare_save',
                [
                    'location' => $location,
                    'request' => $this->getRequest()
                ]
            );
            try {
                $location->save();
                $this->messageManager->addSuccessMessage(__('The location has been saved.'));
                $this->backendSession->setStorepickupLocationData(false);
                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath(
                        'yosto_storepickup/*/edit',
                        [
                            'location_id' => $location->getId(),
                            '_current' => true
                        ]
                    );
                    return $resultRedirect;
                }
                $resultRedirect->setPath('yosto_storepickup/*/');
                return $resultRedirect;
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e, __('Something went wrong while saving the location.'));
            }
            $this->_getSession()->setStorepickupLocationData($data);
            $resultRedirect->setPath(
                'yosto_storepickup/*/edit',
                [
                    'storelocator_id' => $location->getId(),
                    '_current' => true
                ]
            );
            return $resultRedirect;
        }
        $resultRedirect->setPath('yosto_storepickup/*/');
        return $resultRedirect;
    }
}
