<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Block\Review\Product\View\Filters;

use Magento\Framework\DB\Select;
use MageWorkshop\DetailedReview\Observer\Review\PrepareSortCollection;

class Sort extends \Magento\Framework\View\Element\Template
{
    const SORT_TITLE_DATE_NEWEST_FIRST = 'Newest First';
    const SORT_TITLE_DATE_LATEST_FIRST = 'Oldest First';

    /**
     * @return array
     */
    public function getParamsSortOptions()
    {
        return [
            Select::SQL_DESC  => self::SORT_TITLE_DATE_NEWEST_FIRST,
            Select::SQL_ASC   => self::SORT_TITLE_DATE_LATEST_FIRST
        ];
    }

    /**
     * @param string $value
     * @return bool
     */
    public function isSelected($value)
    {
        $defaultValue = $this->getRequest()->getParam(
            $this->getSortParamName(),
            Select::SQL_DESC
        );

        return $value === $defaultValue;
    }

    /**
     * @return string
     */
    public function getSortParamName()
    {
        return PrepareSortCollection::SORT_REQUEST_PARAM;
    }
}
