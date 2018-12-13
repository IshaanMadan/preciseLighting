<?php
namespace SR\DeliveryDate\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
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
            'shipping_delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('quote'),
            'shipping_delivery_comment',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Delivery Comment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'shipping_delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order'),
            'shipping_delivery_comment',
            [
                'type' => 'text',
                'nullable' => false,
                'comment' => 'Delivery Comment',
            ]
        );

        $installer->getConnection()->addColumn(
            $installer->getTable('sales_order_grid'),
            'shipping_delivery_date',
            [
                'type' => 'datetime',
                'nullable' => false,
                'comment' => 'Delivery Date',
            ]
        );

        $setup->endSetup();
    }
}