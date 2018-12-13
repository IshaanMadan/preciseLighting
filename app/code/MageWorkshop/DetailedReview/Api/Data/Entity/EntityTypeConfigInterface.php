<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Api\Data\Entity;

/**
 * @api
 */
interface EntityTypeConfigInterface
{
    /**
     * @return array
     */
    public function getConfig();

    /**
     * @return string
     */
    public function getEntityIdField();

    /**
     * @return string
     */
    public function getAttributeModel();

    /**
     * @return string
     */
    public function getEntityModel();

    /**
     * @return string
     */
    public function getIncrementModel();

    /**
     * @return string
     */
    public function getAdditionalAttributeTable();

    /**
     * @return string
     */
    public function getEntityAttributeCollection();

    /**
     * @return AttributeConfigCollectionInterface
     * @throws \Exception
     */
    public function getAttributesConfigCollection();

    /**
     * @return string
     */
    public function getAttributeFactoryClass();
}
