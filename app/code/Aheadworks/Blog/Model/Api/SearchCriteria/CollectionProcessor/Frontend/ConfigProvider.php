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

namespace Aheadworks\Blog\Model\Api\SearchCriteria\CollectionProcessor\Frontend;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\Collection\AbstractDb;

class ConfigProvider implements CollectionProcessorInterface
{
    private array $generalPaging = [];
    private array $replyCommentPaging = [];
    private ?string $direction = null;

    /**
     * Get paging in collection
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @param AbstractDb $collection
     * @return void
     */
    public function process(SearchCriteriaInterface $searchCriteria, AbstractDb $collection)
    {
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === CommentInterface::PATH) {
                    $numbers = explode('/', $filter->getValue());
                    $rootCommentId = $numbers[0];
                }
                if ($filter->getField() === CommentInterface::REPLY_TO_COMMENT_ID && $filter->getConditionType() === 'null') {
                    $this->generalPaging = [
                        'current_page' => $collection->getCurPage(),
                        'last_page' => $collection->getLastPageNumber()
                    ];
                    $this->setDirectionBySearchCriteria($searchCriteria);
                }
                if ($filter->getField() === CommentInterface::REPLY_TO_COMMENT_ID &&
                    $filter->getConditionType() === 'notnull'
                    && $collection->getSize()) {
                    $this->replyCommentPaging[$rootCommentId] = [
                        'current_page' => $collection->getCurPage(),
                        'last_page' => $collection->getLastPageNumber()
                    ];
                    $this->setDirectionBySearchCriteria($searchCriteria);
                }
            }
        }
    }

    /**
     * Get general current page
     *
     * @return int
     */
    public function getGeneralCurrentPage(): int
    {
        return $this->generalPaging['current_page'] ?? 1;
    }

    /**
     * Get general last page
     *
     * @return int
     */
    public function getGeneralLastPage(): int
    {
        return $this->generalPaging['last_page'] ?? 1;
    }

    /**
     * Get reply comment current page
     *
     * @param int $commentId
     * @return int
     */
    public function getReplyCommentCurrentPage(int $commentId): int
    {
        return $this->replyCommentPaging[$commentId]['current_page'] ?? 1;
    }

    /**
     * Get reply comment last page
     *
     * @param int $commentId
     * @return int
     */
    public function getReplyCommentLastPage(int $commentId): int
    {
        return $this->replyCommentPaging[$commentId]['last_page'] ?? 1;
    }

    /**
     * Get direction
     *
     * @return string|null
     */
    public function getDirection(): ?string
    {
        return $this->direction;
    }

    /**
     * Set direction by search criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return void
     */
    public function setDirectionBySearchCriteria(SearchCriteriaInterface $searchCriteria): void
    {
        foreach ($searchCriteria->getSortOrders() as $sortOrder) {
            $this->direction = $sortOrder->getDirection();
            break;
        }
    }
}
