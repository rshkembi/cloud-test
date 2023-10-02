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
declare(strict_types=1);

namespace Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext;

use Magento\Framework\Api\Search\SearchResultFactory;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Search\EngineResolverInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Search\Model\SearchEngine;
use Psr\Log\LoggerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection\SearchCriteriaResolver;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection\SearchCriteriaResolverFactory;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection\SearchResultApplier;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection\SearchResultApplierFactory;
use Aheadworks\BlogSearch\Model\ThirdPartyModule\ElasticSuite\Adapter;
use Magento\Framework\Search\Search;
use Magento\Framework\Api\SortOrder;

class Collection extends \Aheadworks\Blog\Model\ResourceModel\Post\Collection
{
    /**
     * @var string
     */
    private $queryText;

    /**
     * @var SearchResultInterface
     */
    private $searchResult;

    /**
     * Collection constructor.
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param EventManager $eventManager
     * @param DateTime $dateTime
     * @param MetadataPool $metadataPool
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SearchCriteriaResolverFactory $searchCriteriaResolverFactory
     * @param SearchResultApplierFactory $searchResultApplierFactory
     * @param SearchResultFactory $searchResultFactory
     * @param Adapter $elasticSuiteAdapter
     * @param EngineResolverInterface $engineResolver
     * @param SearchEngine $searchEngine
     * @param Search $search
     * @param AdapterInterface|null $connection
     * @param AbstractDb|null $resource
     * @param string $searchRequestName
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        EventManager $eventManager,
        DateTime $dateTime,
        MetadataPool $metadataPool,
        private readonly FilterBuilder $filterBuilder,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly SearchCriteriaResolverFactory $searchCriteriaResolverFactory,
        private readonly SearchResultApplierFactory $searchResultApplierFactory,
        private readonly SearchResultFactory $searchResultFactory,
        private readonly Adapter $elasticSuiteAdapter,
        private readonly EngineResolverInterface $engineResolver,
        private readonly SearchEngine $searchEngine,
        private readonly Search $search,
        AdapterInterface $connection = null,
        AbstractDb $resource = null,
        private readonly string $searchRequestName = 'aheadworks_blogsearch_post_fulltext'
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $dateTime,
            $metadataPool,
            $connection,
            $resource
        );
    }

    /**
     * Add search query filter
     *
     * @param string $query
     * @return $this
     */
    public function addSearchFilter($query)
    {
        $this->queryText = trim($this->queryText . ' ' . $query);
        return $this;
    }

    /**
     * Render sql select conditions
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        if ($this->isLoaded()) {
            return;
        }

        if ($this->engineResolver->getCurrentSearchEngine() === 'elasticsuite') {
            $this->prepareElasticSuiteFilters();
        } else {
            $this->prepareElasticSearchFilters();
        }

        parent::_renderFiltersBefore();
    }

    /**
     * Prepare elasticSuite filters
     *
     * @return void
     * @throws NoSuchEntityException
     */
    public function prepareElasticSuiteFilters()
    {
        $searchRequest = $this->elasticSuiteAdapter->getRequest($this, $this->searchRequestName);
        $queryResponse = $this->searchEngine->search($searchRequest);
        $docIds = $this->elasticSuiteAdapter->getDocs($queryResponse);
        if (empty($docIds)) {
            $docIds[] = 0;
        }
        $this->getSelect()->where('main_table.id IN (?)', ['in' => $docIds]);
        $this->addOrder(PostInterface::CREATED_AT, SortOrder::SORT_DESC);
    }

    /**
     * Prepare elasticsearch filters
     *
     * @return void
     */
    public function prepareElasticSearchFilters()
    {
        if (strlen(trim($this->queryText))) {
            $this->prepareSearchTermFilter();
            $searchCriteria = $this->getSearchCriteriaResolver()->resolve();
            try {
                /** @var SearchResultInterface $searchResult */
                $this->searchResult = $this->search->search($searchCriteria);
            } catch (\Exception $e) {
                $this->searchResult = $this->createEmptyResult();
                $this->_logger->error($e->getMessage());
            }
        } else {
            $this->searchResult = $this->createEmptyResult();
        }
        $this->getSearchResultApplier($this->searchResult)->apply();
    }

    /**
     * Prepare search term filter for text query.
     *
     * @return void
     */
    private function prepareSearchTermFilter(): void
    {
        if ($this->queryText) {
            $this->filterBuilder->setField('search_term');
            $this->filterBuilder->setValue($this->queryText);
            $this->searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        }
    }

    /**
     * Get search criteria resolver.
     *
     * @return SearchCriteriaResolver
     */
    private function getSearchCriteriaResolver(): SearchCriteriaResolver
    {
        return $this->searchCriteriaResolverFactory->create(
            [
                'builder' => $this->searchCriteriaBuilder,
                'searchRequestName' => $this->searchRequestName,
            ]
        );
    }

    /**
     * Get search result applier.
     *
     * @param SearchResultInterface $searchResult
     * @return SearchResultApplier
     */
    private function getSearchResultApplier(SearchResultInterface $searchResult): SearchResultApplier
    {
        return $this->searchResultApplierFactory->create(
            [
                'collection' => $this,
                'searchResult' => $searchResult,
                /** This variable sets by serOrder method, but doesn't have a getter method. */
                'orders' => $this->_orders,
                'size' => $this->getPageSize(),
                'currentPage' => (int)$this->_curPage,
            ]
        );
    }

    /**
     * Create empty search result
     *
     * @return SearchResultInterface
     */
    private function createEmptyResult()
    {
        return $this->searchResultFactory->create()->setItems([]);
    }
}
