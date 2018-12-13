<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Api\Data\Indexer\Structure;

interface FiledMapperBuilderInterface
{
    const DEFAULT_BUILDER_ALIAS = 'standard';

    /**
     * @param string $fieldName
     * @return array
     */
    public function build($fieldName);
}
