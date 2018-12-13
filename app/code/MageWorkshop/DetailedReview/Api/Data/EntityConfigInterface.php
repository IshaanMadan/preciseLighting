<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Api\Data;

use MageWorkshop\DetailedReview\Api\Data\Entity\EntityTypeConfigInterface;
use MageWorkshop\DetailedReview\Api\Data\Entity\AttributeConfigCollectionInterface;

/**
 * @api
 */
interface EntityConfigInterface
{
    const ENTITY_CONFIG_NOT_FOUND_EXCEPTION = 'Entity config was not found for %1 entity type';

    /**
     * @param string $entityType
     * @return EntityTypeConfigInterface
     */
    public function getEntityTypeConfig($entityType);

    /**
     * @param string $entityType
     * @return AttributeConfigCollectionInterface
     */
    public function getEntityAttributesConfig($entityType);
}
