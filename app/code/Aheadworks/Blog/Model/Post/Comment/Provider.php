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

namespace Aheadworks\Blog\Model\Post\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver;
use Aheadworks\Blog\Model\Post\CommentRepository;
use Aheadworks\Blog\Model\Source\Comment\Status as CommentStatus;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

class Provider
{
    /**
     * Comment count published
     *
     * @var array
     */
    private array $commentNumPublished;

    /**
     * Comment count new
     *
     * @var array
     */
    private array $commentNumNew;

    /**
     * @param CommentRepository $commentRepository
     * @param Resolver $commentSearchCriteriaResolver
     * @param DataObjectProcessor $dataObjectProcessor
     */
    public function __construct(
        private readonly CommentRepository   $commentRepository,
        private readonly Resolver            $commentSearchCriteriaResolver,
        private readonly DataObjectProcessor $dataObjectProcessor
    ) {
    }

    /**
     * Get comment with child
     *
     * @param array $data
     * @return array|CommentInterface[]
     * @throws NoSuchEntityException
     */
    public function getComments(array $data): array
    {
        $comments = $this->getRootComments($data);
        foreach ($comments as $comment) {
            $data[Resolver::PARENT_COMMENT_ID] = (int)$comment->getId();
            $data[Resolver::CURRENT_PAGE] = 1;
            $comment->setChildren($this->getChildComments($data)
            );
        }

        return $this->convertToArray($comments);
    }

    /**
     * Get root comments
     *
     * @param array $data
     * @return CommentInterface[]|array
     * @throws NoSuchEntityException
     */
    public function getRootComments(array $data): array
    {
        $searchCriteria = $this->commentSearchCriteriaResolver->getRootCommentSearchCriteria($data);

        return $this->commentRepository->getList($searchCriteria->create())->getItems();
    }

    /**
     * Get child comments
     *
     * @param array $data
     * @return CommentInterface[]|array
     * @throws NoSuchEntityException
     */
    public function getChildComments(array $data): array
    {
        $searchCriteria = $this->commentSearchCriteriaResolver->getChildCommentSearchCriteria($data);

        return $this->commentRepository->getList($searchCriteria->create())->getItems();
    }

    /**
     * Convert to array
     *
     * @param array|null $comments
     * @return array|null
     */
    private function convertToArray(?array $comments): ?array
    {
        $data = [];
        foreach ($comments as $comment) {
            $data[] = $this->dataObjectProcessor->buildOutputDataArray($comment, CommentInterface::class);
        }

        return $data;
    }

    /**
     * Get counts comments by post id
     *
     * @param array $data
     * @return int
     * @throws NoSuchEntityException
     */
    public function getCountCommentsByPostId(array $data): int
    {
        $rootSearchCriteria = $this->commentSearchCriteriaResolver->getRootCommentSearchCriteria($data);
        $searchResults = $this->commentRepository->getList($rootSearchCriteria->create());
        $count = $searchResults->getTotalCount();
        foreach ($searchResults->getItems() as $comment) {
            $data[Resolver::PARENT_COMMENT_ID] = (int)$comment->getId();
            $childSearchCriteria = $this->commentSearchCriteriaResolver->getCountChildCommentSearchCriteria($data);
            $count += $this->commentRepository->getList($childSearchCriteria->create())->getTotalCount();
        }

        return $count;
    }

    /**
     * Get count published comments by post ids
     *
     * @param array $postIds
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCountPublishedCommentsByPostIds(array $postIds): array
    {
        $key = implode('', $postIds);
        if (!isset($this->commentNumPublished[$key])) {
            $publishedComments = [];
            foreach ($postIds as $postId) {
                $data['post_id'] = (int)$postId;
                $data['status'] = CommentStatus::APPROVE;
                $publishedComments[$postId] = $this->getCountCommentsByPostId($data);
            }
            $this->commentNumPublished[$key] = $publishedComments;
        }

        return $this->commentNumPublished[$key];

    }

    /**
     * Get count new comments by post ids
     *
     * @param array $postIds
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCountNewCommentsByPostIds(array $postIds): array
    {
        $key = implode('', $postIds);
        if (!isset($this->commentNumNew[$key])) {
            $newComments = [];
            foreach ($postIds as $postId) {
                $data['post_id'] = (int)$postId;
                $data['status'] = CommentStatus::PENDING;

                $newComments[$postId] = $this->getCountCommentsByPostId($data);
            }
            $this->commentNumNew[$key] = $newComments;
        }

        return $this->commentNumNew[$key];
    }
}
