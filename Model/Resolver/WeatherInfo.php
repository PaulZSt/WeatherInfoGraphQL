<?php

declare(strict_types=1);

namespace Elogic\WeatherInfoGraphQL\Model\Resolver;

use Elogic\WeatherInfo\Api\WeatherRepositoryInterface;
use Elogic\WeatherInfoGraphQL\Model\Weather\GetList;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Elogic\WeatherInfoGraphQL\Model\Weather\GetListInterface;

class WeatherInfo implements ResolverInterface
{

    /**
     * @var GetListInterface
     */
    private $weatherRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * WeatheList constructor.
     * @param GetList $weatherRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(WeatherRepositoryInterface $weatherRepository, SearchCriteriaBuilder $searchCriteriaBuilder)
    {
        $this->weatherRepository = $weatherRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null): array
    {

        $this->vaildateArgs($args);

        $searchCriteria = $this->searchCriteriaBuilder->build('weather', $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $searchResult = $this->weatherRepository->getList($searchCriteria);

        return [
            'total_count' => $searchResult->getTotalCount(),
            'items' => $searchResult->getItems(),
        ];
    }

    /**
     * @param array $args
     * @throws GraphQlInputException
     */
    private function vaildateArgs(array $args): void
    {
        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
    }
}
