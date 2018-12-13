<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\Core\Model\Module;

interface DetailsDataInterface
{
    /**
     * @return string
     */
    public function getModuleName();

    /**
     * @return string
     */
    public function getModuleCode();
}
