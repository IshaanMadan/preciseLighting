<?php
/**
 * Copyright (c) 2018 MageWorkshop. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace MageWorkshop\DetailedReview\Model\Config;

use Magento\Framework\DataObject;

abstract class AbstractConfig extends DataObject
{
    protected $requiredConfigFields = [];

    protected $optionalConfigFields = [];

    /**
     * AbstractConfig constructor.
     * @param array $requiredConfigFields
     * @param array $optionalConfigFields
     * @param array $data
     */
    public function __construct(
        array $requiredConfigFields,
        array $optionalConfigFields,
        array $data = []
    ) {
        parent::__construct($data);
        $this->requiredConfigFields = $requiredConfigFields;
        $this->optionalConfigFields = $optionalConfigFields;
    }

    /**
     * Get full attribute data via real getters to perform proper data validation
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];

        foreach ($this->requiredConfigFields as $field) {
            $config[$field] = $this->getDataUsingMethod($field);
        }

        foreach ($this->optionalConfigFields as $field) {
            if ($data = $this->getDataUsingMethod($field)) {
                $config[$field] = $data;
            }
        }

        return $config;
    }

    /**
     * @param string $key
     * @param mixed $defaultValue
     * @return mixed
     */
    protected function getDataOrDefault($key, $defaultValue)
    {
        if (!$value = $this->getData($key)) {
            $value = $defaultValue;
        }
        return $value;
    }
}
