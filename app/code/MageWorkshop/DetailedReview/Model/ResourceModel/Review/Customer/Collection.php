<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Report Customers Review collection
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace MageWorkshop\DetailedReview\Model\ResourceModel\Review\Customer;

class Collection extends \Magento\Reports\Model\ResourceModel\Review\Customer\Collection
{

    /**
     * TODO Fixed a problem with getting the collection if you use the table prefix for database.
     * Join customers
     *
     * @return $this
     */
    protected function _joinCustomers()
    {
        /** @var $connection \Magento\Framework\DB\Adapter\AdapterInterface */
        $connection = $this->getConnection();
        //Prepare fullname field result
        $customerFullname = $connection->getConcatSql(['customer.firstname', 'customer.lastname'], ' ');
        $this->getSelect()->reset(
            \Magento\Framework\DB\Select::COLUMNS
        )->joinInner(
            ['customer' => $this->getTable('customer_entity')],
            'customer.entity_id = detail.customer_id',
            []
        )->columns(
            [
                'customer_id' => 'detail.customer_id',
                'customer_name' => $customerFullname,
                'review_cnt' => 'COUNT(main_table.review_id)',
            ]
        )->group(
            'detail.customer_id'
        );
        return $this;
    }
}
