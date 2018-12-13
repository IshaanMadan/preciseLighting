<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Model\Indexer;

use MageWorkshop\DetailedReview\Model\Details;

/**
 * Class AbstractEavIndexer
 * Refresh flat table structure. There is no need to care about attributes because unchanged attribute columns
 * won't be modified by the TableBuilder
 *
 * @package MageWorkshop\DetailedReview\Model\Indexer
 */
class Eav extends \MageWorkshop\DetailedReview\Model\Indexer\AbstractIndexer
{
    protected $entityCode = Details::ENTITY;
}
