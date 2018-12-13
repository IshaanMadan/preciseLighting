<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

class ColumnConfig implements ColumnConfigInterface
{
    /** @var string $name */
    protected $name;

    /** @var string $type */
    protected $type;

    /** @var int|string $length */
    protected $length;

    /** @var array $options */
    protected $options = [];

    /** @var string $comment */
    protected $comment;

    /** @var ForeignKeyConfigInterface $foreignKeyConfig */
    protected $foreignKeyConfig;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * {@inheritdoc}
     */
    public function setLength($length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getForeignKeyConfig()
    {
        return $this->foreignKeyConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function setForeignKeyConfig(ForeignKeyConfigInterface $foreignKeyConfig)
    {
        $this->foreignKeyConfig = $foreignKeyConfig;
        return $this;
    }
}
