<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Plugin\ConfigurableSampleData\Model;

use MageWorkshop\DetailedReview\Model\Indexer\Flat as FlatIndexer;

class ProductPlugin
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * ProductPlugin constructor.
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(\Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
    }

    public function beforeInstall()
    {
        $this->registry->register(FlatIndexer::CHECK_STATE_IN_DATABASE, true);
    }

    public function afterInstall()
    {
        $this->registry->unregister(FlatIndexer::CHECK_STATE_IN_DATABASE);
    }
}
