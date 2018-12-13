<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\DeliveryDate\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Yosto\DeliveryDate\Setup
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'delivery_comment',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Delivery Comment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'delivery_comment',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Delivery Comment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'delivery_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Delivery Date',
            ]
        );

        $setup->endSetup();
    }
}