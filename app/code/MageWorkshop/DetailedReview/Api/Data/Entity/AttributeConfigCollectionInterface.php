<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Api\Data\Entity;

/**
 * @api
 */
interface AttributeConfigCollectionInterface extends \IteratorAggregate
{
    /**
     * @param AttributeConfigInterface $attributeConfig
     * @return $this
     * @throws \Exception
     */
    public function addItem(AttributeConfigInterface $attributeConfig);

    /**
     * @param string $attributeType
     * @return false|AttributeConfigInterface
     * @throws \DomainException
     */
    public function getItem($attributeType);
}
