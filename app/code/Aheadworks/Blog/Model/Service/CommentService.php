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

namespace Aheadworks\Blog\Model\Service;

use Aheadworks\Blog\Api\CommentManagementInterface;
use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Entity\ProcessorInterface as EntityProcessorInterface;
use Magento\Framework\Event\ManagerInterface as EventManagerInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CommentService implements CommentManagementInterface
{
    /**
     * @param CommentRepositoryInterface $commentRepository
     * @param EventManagerInterface $eventManager
     * @param EntityProcessorInterface $creationProcessor
     */
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly EventManagerInterface $eventManager,
        private readonly EntityProcessorInterface $creationProcessor,
    ) {
    }

    /**
     * Create new comment
     *
     * @param CommentInterface $comment
     * @return CommentInterface
     * @throws CouldNotSaveException
     * @throws LocalizedException
     */
    public function createComment(CommentInterface $comment)
    {
        $this->creationProcessor->process($comment);
        $createdComment = $this->commentRepository->save($comment);

        $this->eventManager->dispatch(
            'aw_blog_comment_creation_after',
            [
                'created_comment' => $createdComment,
            ]
        );

        return $createdComment;
    }

    /**
     * Change status comment
     *
     * @param int $commentId
     * @param string $status
     * @return bool
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function changeStatus(int $commentId, string $status): bool
    {
        $result = false;
        try {
            $comment = $this->commentRepository->getById($commentId);
        } catch (NoSuchEntityException) {
            return $result;
        }
        if ($comment->getStatus() !== $status) {
            $comment->setStatus($status);
            $this->updateComment($comment);
            $result = true;
        }

        return $result;
    }

    /**
     * Update existing comment
     *
     * @param CommentInterface $comment
     * @return CommentInterface
     * @throws CouldNotSaveException
     * @throws NoSuchEntityException
     */
    public function updateComment(CommentInterface $comment)
    {
        $originalComment = $this->commentRepository->getById((int)$comment->getId(), true);
        $updatedComment = $this->commentRepository->save($comment);
        $this->eventManager->dispatch(
            'aw_blog_comment_update_after',
            [
                'original_comment' => $originalComment,
                'updated_comment' => $comment,
            ]
        );

        return $updatedComment;
    }

    /**
     * Delete existing comment
     *
     * @param CommentInterface $comment
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function deleteComment(CommentInterface $comment): bool
    {
        return $this->commentRepository->delete($comment);
    }

    /**
     * Delete existing comment by id
     *
     * @param int $commentId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteCommentById(int $commentId): bool
    {
        return $this->commentRepository->deleteById($commentId);
    }
}
