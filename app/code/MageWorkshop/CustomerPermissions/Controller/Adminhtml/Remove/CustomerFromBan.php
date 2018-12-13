<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\CustomerPermissions\Controller\Adminhtml\Remove;

use MageWorkshop\CustomerPermissions\Helper\BanHelper;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Controller\Adminhtml\Index\AbstractMassAction;

class CustomerFromBan extends AbstractMassAction
{
    /** @var BanHelper $banHelper */
    protected $banHelper;

    /**
     * CustomerFromBan constructor.
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
    ) {
        parent::__construct($context, $filter, $collectionFactory);
        $this->banHelper = $banHelper;
    }

    /**
     * @param AbstractCollection $collection
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function massAction(AbstractCollection $collection)
    {
        $collection->addAttributeToSelect('banned_till');
        $successfullyRemovedFromBanList = [];
        try {
            $successfullyRemovedFromBanList = $this->banHelper->removeFromBanList($collection);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        if (empty($successfullyRemovedFromBanList)) {
            $this->messageManager->addNotice(__('No banned users found.'));
        } else {
            $this->messageManager->addSuccess(
                __('Following author(s) was/were removed from ban list: %1', implode(', ', $successfullyRemovedFromBanList))
            );
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('customer/index/index/');
        return $resultRedirect;
    }

}

