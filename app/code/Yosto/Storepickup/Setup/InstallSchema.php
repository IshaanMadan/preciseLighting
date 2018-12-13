<?php
/**
 * Copyright © 2017 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */
namespace Yosto\Storepickup\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Yosto\Storepickup\Setup
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
            'pickup_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Pickup Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'location_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Location id',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'pickup_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Pickup Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'location_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => false,
                'comment' => 'Location id',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'pickup_date',
            [
                'type' => 'datetime',
                'nullable' => true,
                'comment' => 'Pickup Date',
            ]
        );
        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'location_id',
            [
                'type' => Table::TYPE_INTEGER,
                'nullable' => true,
                'comment' => 'Location id',
            ]
        );
        $table = $installer->getConnection()->newTable(
            $installer->getTable('yosto_storepickup_location')
        )
            ->addColumn(
                'location_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                    'identity' => true,
                    'nullable' => false,
                    'primary'  => true,
                    'unsigned' => true,
                ],
                'Location ID'
            )
            ->addColumn(
                'store_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Store Title'
            )
            ->addColumn(
                'address',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Address'
            )
            ->addColumn(
                'city',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'City'
            )
            ->addColumn(
                'state',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'State'
            )
            ->addColumn(
                'pincode',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Pincode'
            )
            ->addColumn(
                'country',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Country'
            )
            ->addColumn(
                'phone',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Phone'
            )
            ->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Email'
            )
            ->addColumn(
                'image',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Image'
            )
            ->addColumn(
                'is_enable',
                \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                'Enable'
            )
            ->addColumn(
                'latitude',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Latitude'
            )
            ->addColumn(
                'longitude',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Longitude'
            )
            ->addColumn(
                'zoom_level',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable => false'],
                'Zoom Level'
            )

            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Updated At'
            )
            ->setComment('Storepickup Table');
            $installer->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
