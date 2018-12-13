<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Core\Model\Module;

abstract class AbstractDetailsData implements DetailsDataInterface
{
    const MODULE_CODE = '';

    /** @var string $publicName */
    protected $publicName = '';

    /**
     * {@inheritdoc}
     */
    public function getModuleName()
    {
        return $this->publicName;
    }

    /**
     * {@inheritdoc}
     */
    public function getModuleCode()
    {
        // We can not use "self::" here, because it refers to the constant value in the abstract class
        /** @var AbstractDetailsData $className */
        $className = get_class($this);
        return constant($className . '::MODULE_CODE');
    }
}
