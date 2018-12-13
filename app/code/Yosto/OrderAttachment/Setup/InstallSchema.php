<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\OrderAttachment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Yosto\OrderAttachment\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'yosto_order_attachment',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Order File Attachment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'yosto_order_attachment',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Order File Attachment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'yosto_order_attachment',
            [
                'type' => 'text',
                'nullable' => true,
                'comment' => 'Order File Attachment',
            ]
        );

        $installer->endSetup();
    }

}