<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Indexer\Structure\FieldMapperBuilder;

use Amasty\ElasticSearch\Api\Data\Indexer\Structure\FiledMapperBuilderInterface;

class Standard implements FiledMapperBuilderInterface
{
    /**
     * @param $fieldName
     * @return array
     */
    public function build($fieldName)
    {
        $fieldMapping = [
            [
                'price_mapping' => [
                    'match' => 'price_*',
                    'match_mapping_type' => 'string',
                    'mapping' => [
                        'type' => 'float'
                    ],
                ],
            ],
            [
                'string_mapping' => [
                    'match' => '*',
                    'match_mapping_type' => 'string',
                    'mapping' => [
                        'type' => 'text',
                        'analyzer' => 'custom'
                    ],
                ],
            ],
        ];

        return $fieldMapping;
    }
}
