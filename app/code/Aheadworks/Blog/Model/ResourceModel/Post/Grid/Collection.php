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
 * @package    Blog
 * @version    2.17.1
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\Blog\Model\ResourceModel\Post\Grid;

use Aheadworks\Blog\Api\CommentsServiceInterface as DisqusCommentsServiceInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Comment\Status as CommentStatus;
use Aheadworks\Blog\Model\Post\Comment\Provider as BuiltinCommentProvider;
use Aheadworks\Blog\Model\Source\Config\Comments\Service;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Store\Model\Store;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Api\Search\DocumentInterface;
use Psr\Log\LoggerInterface;

/**
 * Collection for displaying grid of blog posts
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Collection extends PostCollection implements SearchResultInterface
{
    /**
     * @var AggregationInterface
     */
    private $aggregations;

    /**
     * @param EntityFactoryInterface $entityFactory
     * @param LoggerInterface $logger
     * @param FetchStrategyInterface $fetchStrategy
     * @param ManagerInterface $eventManager
     * @param DateTime $dateTime
     * @param DisqusCommentsServiceInterface $disqusCommentsService
     * @param Config $config
     * @param MetadataPool $metadataPool
     * @param RequestInterface $request
     * @param BuiltinCommentProvider $builtinCommentProvider
     * @param string $mainTable
     * @param string $resourceModel
     * @param string $model
     * @param null $connection
     * @param AbstractDb|null $resource
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        DateTime $dateTime,
        private readonly DisqusCommentsServiceInterface $disqusCommentsService,
        private readonly Config $config,
        MetadataPool $metadataPool,
        private readonly RequestInterface $request,
        private readonly BuiltinCommentProvider $builtinCommentProvider,
        $mainTable,
        $resourceModel,
        $model = Document::class,
        $connection = null,
        AbstractDb $resource = null
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
        $this->_init($model, $resourceModel);
        $this->setMainTable($mainTable);
    }

    /**
     * {@inheritdoc}
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     *  Set aggregations
     *
     * @param AggregationInterface $aggregations
     * @return $this
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setSearchCriteria(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setTotalCount($totalCount)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setItems(array $items = null)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field == PostInterface::CATEGORY_IDS
            || (is_array($field) && in_array(PostInterface::CATEGORY_IDS, $field))
        ) {
            $this->addFilter('category_id', ['in' => $condition], 'public');
            return $this;
        }
        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * Retrieve collection items
     *
     * @return DocumentInterface[]
     * @throws NoSuchEntityException
     */
    public function getItems()
    {
        $this->addAuthorFilterIfNeeded();
        $items = parent::getItems();
        $postIds = $this->getIds();
        if ($this->config->getCommentType() === Service::DISQUS) {
            $publishedComments = $this->disqusCommentsService->getPublishedCommNumBundle($postIds, Store::DEFAULT_STORE_ID);
            $newComments = $this->disqusCommentsService->getNewCommNumBundle($postIds, Store::DEFAULT_STORE_ID);
        } else {
            $publishedComments = $this->builtinCommentProvider->getCountPublishedCommentsByPostIds($postIds);
            $newComments = $this->builtinCommentProvider->getCountNewCommentsByPostIds($postIds);
        }

        foreach ($items as $item) {
            $postId = $item->getData('id');
            $item->setData('published_comments', $publishedComments[$postId]);
            $item->setData('new_comments', $newComments[$postId]);
        }

        return $items;
    }

    /**
     * Get items IDs
     *
     * @return array
     */
    private function getIds()
    {
        $ids = [];
        foreach ($this->_items as $item) {
            $ids[] = $this->_getItemId($item);
        }
        return $ids;
    }

    /**
     * Add author filter if needed
     *
     * @return $this
     */
    private function addAuthorFilterIfNeeded()
    {
        $authorId = $this->request->getParam(PostInterface::AUTHOR_ID, false);
        if ($authorId) {
            $this->addFieldToFilter(PostInterface::AUTHOR_ID, $authorId);
        }

        return $this;
    }


}
