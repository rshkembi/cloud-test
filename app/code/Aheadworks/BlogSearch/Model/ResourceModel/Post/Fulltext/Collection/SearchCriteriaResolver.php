<?php
/**
 * Aheadworks Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://aheadworks.com/end-user-license-agreement/
 *
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection;

use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchCriteria;

/**
 * Class SearchCriteriaResolver
 */
class SearchCriteriaResolver
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $builder;

    /**
     * @var string
     */
    private $searchRequestName;

    /**
     * SearchCriteriaResolver constructor.
     * @param SearchCriteriaBuilder $builder
     * @param string $searchRequestName
     */
    public function __construct(
        SearchCriteriaBuilder $builder,
        string $searchRequestName
    ) {
        $this->builder = $builder;
        $this->searchRequestName = $searchRequestName;
    }

    /**
     * Resolve specific attributes for search criteria
     *
     * @return SearchCriteria
     */
    public function resolve() : SearchCriteria
    {
        $searchCriteria = $this->builder->create();
        $searchCriteria->setRequestName($this->searchRequestName);
        $searchCriteria->setSortOrders(null);

        return $searchCriteria;
    }
}
