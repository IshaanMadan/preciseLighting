<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Model\Config;

use MageWorkshop\DetailedReview\Api\Data\Entity\AttributeConfigInterface;
use MageWorkshop\DetailedReview\Api\Data\Entity\AttributeConfigCollectionInterface;

abstract class AbstractAttributeCollection implements AttributeConfigCollectionInterface
{
    const DUPLICATED_ATTRIBUTE_DEFINITION_EXCEPTION = 'Attribute configuration with the same type already exists';

    const MISSED_ATTRIBUTE_CONFIGURATION_EXCEPTION = 'No attribute configuration found for input type %s';

    /** @var array $items */
    protected $items = [];

    /**
     * {@inheritdoc}
     */
    public function addItem(AttributeConfigInterface $attributeConfig)
    {
        if (isset($items[$attributeConfig->getFrontendInput()])) {
            throw new \LogicException(self::DUPLICATED_ATTRIBUTE_DEFINITION_EXCEPTION);
        }

        $this->items[$attributeConfig->getFrontendInput()] = $attributeConfig;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($attributeType)
    {
        if (!isset($this->items[$attributeType])) {
            throw new \DomainException(sprintf(self::MISSED_ATTRIBUTE_CONFIGURATION_EXCEPTION, $attributeType));
        }
        return $this->items[$attributeType];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
