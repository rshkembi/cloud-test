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

namespace Aheadworks\Blog\Model\Post\Comment\SearchCriteria;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Source\Comment\Status;
use Aheadworks\Blog\Model\DateTime\Formatter;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;

class Builder
{
    public const DEFAULT_CURRENT_PAGE = 1;
    public const DEFAULT_PAGE_SIZE = 5;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteria;

    public function __construct(
        private readonly StoreManagerInterface $storeManager,
        private readonly SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        private readonly SortOrderBuilder $sortOrderBuilder,
        private readonly Formatter $dateFormatter
    ) {
    }

    /**
     * Create search criteria
     *
     * @return Builder
     */
    public function createSearchCriteria(): self
    {
        $this->searchCriteria = $this->searchCriteriaBuilderFactory->create();

        return $this;
    }

    /**
     * Add store id filter
     *
     * @param int|null $storeId
     * @return $this
     * @throws NoSuchEntityException
     */
    public function addStoreIdFilter(?int $storeId = null): self
    {
        if (!$storeId) {
            $storeId = (int)$this->storeManager->getStore()->getId();
        }

        $this->searchCriteria->addFilter(CommentInterface::STORE_ID, $storeId);

        return $this;
    }

    /**
     * Add status filter
     *
     * @param string|null $status
     * @return $this
     */
    public function addStatusFilter(?string $status): self
    {
        if (!$status) {
            $status = Status::APPROVE;
        }

        $this->searchCriteria->addFilter(CommentInterface::STATUS, $status);

        return $this;
    }

    /**
     * Add post id filter
     *
     * @param int $postId
     * @return $this
     */
    public function addPostIdFilter(int $postId): self
    {
        $this->searchCriteria->addFilter(CommentInterface::POST_ID, $postId);

        return $this;
    }

    /**
     * Add path filter
     *
     * @param int $commentId
     * @return $this
     */
    public function addPathFilter(int $commentId): self
    {
        $this->searchCriteria->addFilter(CommentInterface::PATH, $commentId . '/%', 'like');

        return $this;
    }

    /**
     * Add created at filter
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    public function addCreatedAtFilter(): self
    {
        $storeId = $this->storeManager->getStore(true)->getId();
        $currentDate = $this->dateFormatter->getLocalizedDateTime(null ,$storeId);

        $this->searchCriteria->addFilter(CommentInterface::CREATED_AT, $currentDate,'lteq');

        return $this;
    }

    /**
     * Add reply to comment id filter
     *
     * @param string|null $conditionType
     * @return $this
     */
    public function addReplyToCommentIdFilter(?string $conditionType): self
    {
        if (!$conditionType) {
            $conditionType = 'null';
        }

        $this->searchCriteria->addFilter(CommentInterface::REPLY_TO_COMMENT_ID, null, $conditionType);

        return $this;
    }

    /**
     * Set sort orders
     *
     * @param string|null $field
     * @param string|null $sortOrder
     * @return $this
     */
    public function setSortOrders(?string $field, ?string $sortOrder): self {
        if (!$field) {
            $field = CommentInterface::CREATED_AT;
        }
        if (!$sortOrder) {
            $sortOrder = SortOrder::SORT_DESC;
        }
        $sortOrders[] = $this->sortOrderBuilder->setField($field)
            ->setDirection($sortOrder)
            ->create();

        $this->searchCriteria->setSortOrders($sortOrders);

        return $this;
    }

    /**
     * Set current page
     *
     * @param int|null $currentPage
     * @return $this
     */
    public function setCurrentPage(?int $currentPage): self
    {
        if (!$currentPage) {
            $currentPage = self::DEFAULT_CURRENT_PAGE;
        }

        $this->searchCriteria->setCurrentPage($currentPage);

        return $this;
    }

    /**
     * Set page size
     *
     * @param int|null $pageSize
     * @return $this
     */
    public function setPageSize(?int $pageSize): self
    {
        if (!$pageSize) {
            $pageSize = self::DEFAULT_PAGE_SIZE;
        }

        $this->searchCriteria->setPageSize($pageSize);

        return $this;
    }

    /**
     * Get search criteria builder
     *
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteria(): SearchCriteriaBuilder
    {
        return $this->searchCriteria;
    }
}
