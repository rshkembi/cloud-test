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

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\NoSuchEntityException;

class Resolver
{
    public const STORE_ID = 'store_id';
    public const POST_ID = 'post_id';
    public const STATUS = 'status';
    public const PARENT_COMMENT_ID = 'parent_comment_id';
    public const SORT_ORDER_FIELD = 'sort_order_field';
    public const SORT_ORDER_DIRECTION = 'sort_order_direction';
    public const CURRENT_PAGE = 'current_page';
    public const ROOT_COMMENT_QTY = 'root_comment_qty';
    public const CHILD_COMMENT_QTY = 'child_comment_qty';

    /**
     * @param Builder $commentCriteriaBuilder
     */
    public function __construct(
        private readonly Builder $commentCriteriaBuilder
    ) {
    }

    /**
     * Get root comment search criteria
     *
     * @param array $data
     * @return SearchCriteriaBuilder
     * @throws NoSuchEntityException
     */
    public function getRootCommentSearchCriteria(array $data): SearchCriteriaBuilder
    {
        return $this->commentCriteriaBuilder->createSearchCriteria()
            ->addStoreIdFilter($data[self::STORE_ID] ?? null)
            ->addPostIdFilter($data[self::POST_ID])
            ->addReplyToCommentIdFilter(null)
            ->addStatusFilter($data[self::STATUS] ?? null)
            ->setSortOrders($data[self::SORT_ORDER_FIELD] ?? null, $data[self::SORT_ORDER_DIRECTION] ?? null)
            ->setCurrentPage($data[self::CURRENT_PAGE] ?? null)
            ->setPageSize($data[self::ROOT_COMMENT_QTY] ?? null)
            ->addCreatedAtFilter()
            ->getSearchCriteria();
    }

    /**
     * Get child comment search criteria
     *
     * @param array $data
     * @return SearchCriteriaBuilder
     * @throws NoSuchEntityException
     */
    public function getChildCommentSearchCriteria(array $data): SearchCriteriaBuilder
    {
        return $this->commentCriteriaBuilder->createSearchCriteria()
            ->addStoreIdFilter($data[self::STORE_ID] ?? null)
            ->addPostIdFilter($data[self::POST_ID])
            ->addPathFilter($data[self::PARENT_COMMENT_ID])
            ->addReplyToCommentIdFilter('notnull')
            ->addStatusFilter($data[self::STATUS] ?? null)
            ->setSortOrders($data[self::SORT_ORDER_FIELD] ?? null, $data[self::SORT_ORDER_DIRECTION] ?? null)
            ->setCurrentPage($data[self::CURRENT_PAGE] ?? null)
            ->setPageSize($data[self::CHILD_COMMENT_QTY] ?? null)
            ->addCreatedAtFilter()
            ->getSearchCriteria();
    }

    /**
     * Get count child comment search criteria
     *
     * @param array $data
     * @return SearchCriteriaBuilder
     * @throws NoSuchEntityException
     */
    public function getCountChildCommentSearchCriteria(array $data): SearchCriteriaBuilder
    {
        return $this->commentCriteriaBuilder->createSearchCriteria()
            ->addStoreIdFilter($data[self::STORE_ID] ?? null)
            ->addPostIdFilter($data[self::POST_ID])
            ->addStatusFilter($data[self::STATUS] ?? null)
            ->addPathFilter($data[self::PARENT_COMMENT_ID])
            ->addCreatedAtFilter()
            ->getSearchCriteria();
    }
}
