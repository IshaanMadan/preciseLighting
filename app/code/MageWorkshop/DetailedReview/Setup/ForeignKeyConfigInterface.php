<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Setup;

interface ForeignKeyConfigInterface
{
    /**
     * @return string
     */
    public function getFkName();

    /**
     * @param string $fkName
     * @return $this
     */
    public function setFkName($fkName);

    /**
     * @return string
     */
    public function getColumn();

    /**
     * @param string $column
     * @return $this
     */
    public function setColumn($column);

    /**
     * @return string
     */
    public function getReferenceTable();

    /**
     * @param string $referenceTable
     * @return $this
     */
    public function setReferenceTable($referenceTable);

    /**
     * @return string
     */
    public function getReferenceColumn();

    /**
     * @param string $referenceColumn
     * @return $this
     */
    public function setReferenceColumn($referenceColumn);

    /**
     * @return string
     */
    public function getOnDelete();

    /**
     * @param string $onDelete
     * @return $this
     */
    public function setOnDelete($onDelete);
}
