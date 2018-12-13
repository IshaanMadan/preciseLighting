<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

interface ColumnConfigInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getLength();

    /**
     * @param int|string $length
     * @return $this
     */
    public function setLength($length);

    /**
     * @return string
     */
    public function getOptions();

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     * @return $this
     */
    public function setComment($comment);

    /**
     * @return null|ForeignKeyConfigInterface
     */
    public function getForeignKeyConfig();

    /**
     * @param ForeignKeyConfigInterface $foreignKeyConfig
     * @return $this
     */
    public function setForeignKeyConfig(ForeignKeyConfigInterface $foreignKeyConfig);
}
