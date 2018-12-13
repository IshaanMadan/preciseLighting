<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use MageWorkshop\DetailedReview\Model\Details;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Customer setup factory
     *
     * @var DetailsSetup
     */
    protected $detailsSetup;

    /**
     * Init
     *
     * @param DetailsSetup $detailsSetup
     */
    public function __construct(DetailsSetup $detailsSetup)
    {
        $this->detailsSetup = $detailsSetup;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Install default structure
        $this->detailsSetup->installEntitySchema();

        $installer->endSetup();
    }
}
