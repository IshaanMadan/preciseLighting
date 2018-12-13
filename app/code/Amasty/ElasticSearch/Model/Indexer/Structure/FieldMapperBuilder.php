<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Indexer\Structure;

use Amasty\ElasticSearch\Api\Data\Indexer\Structure\FiledMapperBuilderInterface;
use Amasty\ElasticSearch\Model\Indexer\Structure\FieldMapperBuilder\Standard;

class FieldMapperBuilder implements FiledMapperBuilderInterface
{

    /**
     * @var FiledMapperBuilder[]
     */
    private $fieldMappers;

    public function __construct(
        Standard $standardBuilder,
        array $fieldMappers = []
    ) {
        $this->fieldMappers[FiledMapperBuilderInterface::DEFAULT_BUILDER_ALIAS] = $standardBuilder;
        foreach ($fieldMappers as $alias => $mapper) {
            if ($mapper instanceof FiledMapperBuilderInterface) {
                $this->fieldMappers[$alias] = $mapper;
            }
        }
    }

    /**
     * @param string $fieldName
     * @return array
     */
    public function build($fieldName)
    {
        $builder = (isset($this->fieldMappers[$fieldName]))
             ? $this->fieldMappers[$fieldName]
                : $this->fieldMappers[FiledMapperBuilderInterface::DEFAULT_BUILDER_ALIAS];

        return $builder->build($fieldName);
    }
}
