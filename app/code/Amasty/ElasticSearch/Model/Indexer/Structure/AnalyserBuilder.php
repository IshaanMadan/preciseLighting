<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Indexer\Structure;

use Amasty\ElasticSearch\Api\Data\Indexer\Structure\AnalyzerBuilderInterface;
use Amasty\ElasticSearch\Api\StopWordRepositoryInterface;

class AnalyserBuilder implements AnalyzerBuilderInterface
{
    /**
     * @var StopWordRepositoryInterface
     */
    private $stopWordRepository;

    public function __construct(
        StopWordRepositoryInterface $stopWordRepository
    ) {
        $this->stopWordRepository = $stopWordRepository;
    }

    /**
     * @param int $storeId
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function build($storeId)
    {
        $analyser = [
            'analyzer' => [
                'custom' => [
                    'type'      => 'custom',
                    'tokenizer' => 'whitespace',
                    'filter'    => [
                        'lowercase',
                        'stop'
                    ],
                ],
            ],
        ];

        return $analyser;
    }
}
