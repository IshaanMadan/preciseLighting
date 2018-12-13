<?php
/**
 * Copyright © MageWorx. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MageWorx\OptionSkuPolicy\Model\Attribute\Option;

use MageWorx\OptionBase\Model\AttributeInterface;
use MageWorx\OptionSkuPolicy\Helper\Data as Helper;

class SkuPolicy implements AttributeInterface
{
    /**
     * @var mixed
     */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Helper::KEY_SKU_POLICY;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableName()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function clearData()
    {
        return;
    }

    /**
     * {@inheritdoc}
     */
    public function applyData($entity, $options)
    {
        $this->entity = $entity;

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function prepareData($object)
    {
        return;
    }
}
