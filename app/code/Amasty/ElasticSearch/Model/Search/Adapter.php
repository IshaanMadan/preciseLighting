<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ElasticSearch
 */


namespace Amasty\ElasticSearch\Model\Search;

use Amasty\ElasticSearch\Model\Client\ClientRepositoryInterface;
use Magento\Framework\Search\AdapterInterface;
use Magento\Framework\Search\RequestInterface;
use Amasty\ElasticSearch\Model\Search\GetResponse\GetAggregations;

class Adapter implements AdapterInterface
{
    /**
     * @var GetRequestQuery
     */
    private $getRequestQuery;

    /**
     * @var GetResponse
     */
    private $getElasticResponse;

    /**
     * @var GetAggregations
     */
    private $getAggregations;

    /**
     * @var ClientRepositoryInterface
     */
    private $clientRepository;

    public function __construct(
        ClientRepositoryInterface $clientRepository,
        GetAggregations $getAggregations,
        GetRequestQuery $getRequestQuery,
        GetResponse $getElasticResponse
    ) {
        $this->getAggregations = $getAggregations;
        $this->getRequestQuery = $getRequestQuery;
        $this->getElasticResponse = $getElasticResponse;
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param RequestInterface $request
     * @return \Magento\Framework\Search\Response\QueryResponse
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function query(RequestInterface $request)
    {
        $client = $this->clientRepository->get();
        $requestQuery = $this->getRequestQuery->execute($request);
        $elasticResponse = $client->search($requestQuery);
        $elasticDocuments = isset($elasticResponse['hits']['hits']) ? $elasticResponse['hits']['hits'] : [];
        $aggregations = $this->getAggregations->execute($request, $elasticResponse);
        $queryResponse = $this->getElasticResponse->execute($elasticDocuments, $aggregations);

        return $queryResponse;
    }
}
