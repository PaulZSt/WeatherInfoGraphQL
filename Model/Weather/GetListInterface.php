<?php

declare(strict_types=1);

namespace Elogic\WeatherInfoGraphQL\Model\Weather;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * @api
 */
interface GetListInterface
{
    /**
     * @param SearchCriteriaInterface|null $searchCriteria
     */
    public function execute(SearchCriteriaInterface $searchCriteria = null);
}
