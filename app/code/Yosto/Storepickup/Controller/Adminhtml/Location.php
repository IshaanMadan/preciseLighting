<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml;

/**
 * Class Location
 * @package Yosto\Storepickup\Controller\Adminhtml
 */
abstract class Location extends \Magento\Backend\App\Action
{
    /**
     * @var \Yosto\Storepickup\Model\LocationFactory
     */
    protected $locationFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magento\Framework\Controller\Result\RedirectFactory
     */
    protected $resultRedirectFactory;

    /**
     * Location constructor.
     * @param \Yosto\Storepickup\Model\LocationFactory $locationFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Yosto\Storepickup\Model\LocationFactory $locationFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->locationFactory   = $locationFactory;
        $this->coreRegistry          = $coreRegistry;
        $this->resultRedirectFactory = $context->getResultRedirectFactory();
        parent::__construct($context);
    }

    /**
     * @return \Yosto\Storepickup\Model\Location
     */
    protected function initLocation()
    {
        $locationId  = $this->getRequest()->getParam('location_id');
        /** @var \Yosto\Storepickup\Model\Location $location */
        $location    = $this->locationFactory->create();
        if ($locationId) {
            $location->load($locationId);
        }
        $this->coreRegistry->register('yosto_storepickup_location', $location);
        return $location;
    }
}
