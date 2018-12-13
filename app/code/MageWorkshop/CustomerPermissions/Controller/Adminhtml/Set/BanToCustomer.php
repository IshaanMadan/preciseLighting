<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Controller\Adminhtml\Set;

use MageWorkshop\CustomerPermissions\Helper\BanHelper;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Customer\Controller\Adminhtml\Index\AbstractMassAction;

class BanToCustomer extends AbstractMassAction
{
    /** @var BanHelper $banHelper */
    protected $banHelper;

    /**
     * BanToCustomer constructor.
     * @param BanHelper $banHelper
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        BanHelper $banHelper
    )
    {
        parent::__construct($context, $filter, $collectionFactory);
        $this->banHelper = $banHelper;
    }

    /**
     * @param AbstractCollection $customerCollection
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function massAction(AbstractCollection $customerCollection)
    {
        $successfullyBanned = [];
        $customerCollection->addAttributeToSelect('banned_till');
        $banPeriod = $this->getRequest()->getParam('ban_period');
        try {
            foreach ($customerCollection as $customer) {
                /** @var \Magento\Customer\Model\Backend\Customer $customer */
                $this->banHelper->banCustomer($customer, $banPeriod);
                $successfullyBanned[] = $customer->getName();
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        $this->messageManager->addSuccess(
            __('Following customer(s) was/were banned: %1', implode(', ', $successfullyBanned))
        );
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('customer/index/index/');
        return $resultRedirect;
    }
}
