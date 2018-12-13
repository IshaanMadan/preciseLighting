<?php
/**
 * Copyright © 2018 x-mage2(Yosto). All rights reserved.
 * See README.md for details.
 */

namespace Yosto\Opc\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Yosto\Opc\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.0', '<')) {
            $connection = $installer->getConnection();
            $connection->addColumn(
                $setup->getTable('sales_order'),
                'yosto_opc_order_comment',
                [
                    "type" => Table::TYPE_TEXT,
                    "nullable" => true,
                    "comment" => "Order Comment"
                ]
            );
            $connection->addColumn(
                $setup->getTable('sales_order'),
                'yosto_opc_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Gift Wrap Amount"
                ]
            );
            $connection->addColumn(
                $setup->getTable('sales_order'),
                'yosto_opc_base_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Base Gift Wrap Amount"
                ]
            );
            $connection->addColumn(
                $setup->getTable('sales_invoice'),
                'yosto_opc_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Gift Wrap Amount"
                ]
            );
            $connection->addColumn(
                $setup->getTable('sales_invoice'),
                'yosto_opc_base_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Base Gift Wrap Amount"
                ]
            );

            $connection->addColumn(
                $setup->getTable('sales_creditmemo'),
                'yosto_opc_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Gift Wrap Amount"
                ]
            );

            $connection->addColumn(
                $setup->getTable('sales_creditmemo'),
                'yosto_opc_base_giftwrap_amount',
                [
                    "type" => Table::TYPE_DECIMAL,
                    "size" => "12,4",
                    "nullable" => true,
                    "comment" => "Base Gift Wrap Amount"
                ]
            );

        }
        $installer->endSetup();
    }

}