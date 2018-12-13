<?php
/**
​ * ​ ​ Webinse
​ *
​ * ​ ​ PHP​ ​ Version​ ​ 7.0.22
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */
/**
​ * ​ ​ Comment​ ​ for​ ​ file
​ *
​ * ​ ​ @category     Webinse
​ * ​ ​ @package    ​ ​ Webinse_RegionManager
​ * ​ ​ @author       Webinse​ ​ Team​ ​ <info@webinse.com>
​ * ​ ​ @copyright  ​ ​ 2018 ​ Webinse​ ​ Ltd.​ ​ (https://www.webinse.com)
​ * ​ ​ @license    ​ ​ http://opensource.org/licenses/OSL-3.0​ ​ The​ ​ Open​ ​ Software​ ​ License​ ​ 3.0
​ */
namespace Webinse\RegionManager\Setup;

use Magento\Framework\Setup;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements Setup\InstallSchemaInterface
{
    public function install(Setup\SchemaSetupInterface $setup, Setup\ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable(
            $setup->getTable('webinse_states')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->setComment(
            'Webinse States'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('webinse_cities')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->addColumn(
            'cities_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Cities name'
        )->setComment(
            'Webinse Cities'
        );
        $setup->getConnection()->createTable($table);

        $table = $setup->getConnection()->newTable(
            $setup->getTable('webinse_zip')
        )->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'auto_increment' => true, 'nullable'=>false, 'primary' => true],
            'ID'
        )->addColumn(
            'states_name',
            Table::TYPE_TEXT,
            255,
            [],
            'States name'
        )->addColumn(
            'cities_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Cities name'
        )->addColumn(
            'zip_code',
            Table::TYPE_TEXT,
            255,
            [],
            'ZIP code'
        )->setComment(
            'Webinse ZIP'
        );
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}