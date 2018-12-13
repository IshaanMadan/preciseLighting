<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Observer\Review;

class PrepareFilterCollection extends AbstractPrepareCollection
{
    const FILTER_REQUEST_PARAM = 'review_filter_by_date';
    
    /** @var \MageWorkshop\DetailedReview\Helper\DateFilterInterval $dateFilterHelper */
    protected $dateFilterHelper;

    /**
     * PrepareFilterCollection constructor.
     * @param \MageWorkshop\DetailedReview\Helper\DateFilterInterval $dateFilterHelper
     * @param \Magento\Framework\App\RequestInterface $request
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \MageWorkshop\DetailedReview\Helper\DateFilterInterval $dateFilterHelper
    ) {
        parent::__construct($request);
        $this->dateFilterHelper = $dateFilterHelper;
    }
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return mixed|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Review\Model\ResourceModel\Review\Collection $collection */
        $collection = $observer->getEvent()->getData('collection');

        if ($dateInterval = $this->dateFilterHelper->getDateInterval(
            $this->getRequestParam(self::FILTER_REQUEST_PARAM)
        )) {
            $collection->addFieldToFilter('main_table.created_at', ['gteq' => $dateInterval]);
        }
    }
}
