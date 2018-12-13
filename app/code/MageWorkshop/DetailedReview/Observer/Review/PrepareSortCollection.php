<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Observer\Review;

use Magento\Framework\DB\Select;

class PrepareSortCollection extends AbstractPrepareCollection
{
    const SORT_REQUEST_PARAM = 'review_sort_direction';
    
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /** @var \Magento\Review\Model\ResourceModel\Review\Collection $collection */
        $collection = $observer->getEvent()->getData('collection');
        $direction = $this->getRequestParam(self::SORT_REQUEST_PARAM) === Select::SQL_ASC
            ? Select::SQL_ASC
            : Select::SQL_DESC;

        $collection->getSelect()->order('main_table.created_at ' . $direction);
    }
}
