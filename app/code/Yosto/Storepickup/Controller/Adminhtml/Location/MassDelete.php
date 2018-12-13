<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Controller\Adminhtml\Location;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * Mass Action Filter
     *
     * @var \Magento\Ui\Component\MassAction\Filter
     */
    protected $filter;

    /**
     * Collection Factory
     *
     * @var \Yosto\Storepickup\Model\ResourceModel\Location\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * constructor
     *
     * @param \Magento\Ui\Component\MassAction\Filter $filter
     * @param \Yosto\Storepickup\Model\ResourceModel\Location\CollectionFactory $collectionFactory
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Ui\Component\MassAction\Filter $filter,
        \Yosto\Storepickup\Model\ResourceModel\Location\CollectionFactory $collectionFactory,
        \Magento\Backend\App\Action\Context $context
    ) {
    
        $this->filter            = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }


    /**
     * execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());

        $delete = 0;
        try{
            foreach ($collection as $item) {
                /** @var \Yosto\Storepickup\Model\Location $item */
                $item->delete();
                $delete++;
            }
            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $delete));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}
