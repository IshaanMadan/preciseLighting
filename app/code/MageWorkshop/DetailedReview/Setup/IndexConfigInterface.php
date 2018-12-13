<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

interface IndexConfigInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $fkName
     * @return $this
     */
    public function setName($fkName);

    /**
     * @return string|array
     */
    public function getColumn();

    /**
     * @param string|array $column
     * @return $this
     */
    public function setColumn($column);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options);
}
