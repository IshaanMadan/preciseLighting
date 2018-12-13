<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Config;

class Data extends \Magento\Framework\Config\Data
{
    const CACHE_ID = 'mageworkshop_eav_attributes_config';

    /**
     * @var \Magento\Framework\Mview\View\State\CollectionInterface
     */
    protected $stateCollection;

    /**
     * @param \MageWorkshop\DetailedReview\Config\Reader $reader
     * @param \Magento\Framework\Config\CacheInterface $cache
     */
    public function __construct(
        \MageWorkshop\DetailedReview\Config\Reader $reader,
        \Magento\Framework\Config\CacheInterface $cache
    ) {
        parent::__construct($reader, $cache, self::CACHE_ID);
    }
}
