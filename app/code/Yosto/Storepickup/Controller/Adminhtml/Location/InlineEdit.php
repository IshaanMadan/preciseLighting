<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml\Location;

/**
 * Class InlineEdit
 * @package Yosto\Storepickup\Controller\Adminhtml\Location
 */
abstract class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @var \Yosto\Storepickup\Model\LocationFactory
     */
    protected $locationFactory;

    /**
     * InlineEdit constructor.
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \Yosto\Storepickup\Model\LocationFactory $locationFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Yosto\Storepickup\Model\LocationFactory $locationFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
    
        $this->jsonFactory         = $jsonFactory;
        $this->locationFactory = $locationFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];
        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }
        foreach (array_keys($postItems) as $locationId) {
            /** @var \Yosto\Storepickup\Model\Location $location */
            $location = $this->locationFactory->create()->load($locationId);
            try {
                $locationData = $postItems[$locationId];
                $location->addData($locationData);
                $location->save();
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithLocationId($location, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithLocationId($location, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithLocationId(
                    $location,
                    __('Something went wrong while saving the Location.')
                );
                $error = true;
            }
        }
        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * @param \Yosto\Storepickup\Model\Location $location
     * @param $errorText
     * @return string
     */
    protected function getErrorWithLocationId(\Yosto\Storepickup\Model\Location $location, $errorText)
    {
        return '[Location ID: ' . $location->getId() . '] ' . $errorText;
    }
}
