<?php

declare(strict_types=1);

namespace Elogic\WeatherInfoGraphQL\Model\Weather;

use Elogic\WeatherInfo\Model\ResourceModel\Weather\Collection;
use Elogic\WeatherInfo\Model\ResourceModel\Weather\CollectionFactory;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;

class GetList implements GetListInterface
{
    /**
     * @var CollectionFactory
     */
    private $weatherCollectionFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SearchResultsInterfaceFactory
     */
    private $weatherSearchResultsInterfaceFactory;

    /**
     * @param CollectionFactory $weatherCollectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchResultsInterfaceFactory $weatherSearchResultsInterfaceFactory
     */
    public function __construct(
        CollectionFactory $weatherCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SearchResultsInterfaceFactory $weatherSearchResultsInterfaceFactory
    ) {
        $this->weatherCollectionFactory = $weatherCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->weatherSearchResultsInterfaceFactory = $weatherSearchResultsInterfaceFactory;
    }

    /**
     * @param SearchCriteriaInterface|null $searchCriteria
     */
    public function execute(SearchCriteriaInterface $searchCriteria = null)
    {
        /** @var WeatherCollectionFactory $weatherCollectionFactory */
        $weatherCollectionFactory = $this->weatherCollectionFactoryFactory->create();
        if (null === $searchCriteria) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
        } else {
            $this->collectionProcessor->process($searchCriteria, $weatherCollectionFactory);
        }
      
        $searchResult = $this->weatherSearchResultsInterfaceFactory->create();
        $searchResult->setItems($weatherCollectionFactory->getItems());
        $searchResult->setTotalCount($weatherCollectionFactory->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
