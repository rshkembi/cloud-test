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

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Source\Comment\Status as CommentStatusSourceModel;
use Magento\Framework\Exception\NoSuchEntityException;

class Checker
{
    /**
     * @param CommentStatusSourceModel $commentStatusSourceModel
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        private readonly CommentStatusSourceModel $commentStatusSourceModel,
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * Check if comment has been published
     *
     * @param CommentInterface $originalComment
     * @param CommentInterface $updatedComment
     * @return bool
     */
    public function isPublished(
        CommentInterface $originalComment,
        CommentInterface $updatedComment
    ):bool {
        return in_array($updatedComment->getStatus(), $this->commentStatusSourceModel->getDisplayStatuses(), true)
            && $this->isStatusChanged($originalComment, $updatedComment);
    }

    /**
     * Check if comment has been rejected
     *
     * @param CommentInterface $originalComment
     * @param CommentInterface $updatedComment
     * @return bool
     */
    public function isRejected(
        CommentInterface $originalComment,
        CommentInterface $updatedComment
    ): bool {
        return in_array(
                $updatedComment->getStatus(),
                $this->commentStatusSourceModel->getRejectedStatusList(),
                true
            )
            && $this->isStatusChanged($originalComment, $updatedComment);
    }

    /**
     * Check if comment status has been changed
     *
     * @param CommentInterface $originalComment
     * @param CommentInterface $updatedComment
     * @return bool
     */
    public function isStatusChanged(
        CommentInterface $originalComment,
        CommentInterface $updatedComment
    ): bool {
        return $originalComment->getStatus() !== $updatedComment->getStatus();
    }

    /**
     * Can send owner comment notification
     *
     * @param CommentInterface $originalComment
     * @param CommentInterface $updatedComment
     * @return bool
     */
    public function canSendOwnerCommentNotification(
        CommentInterface $originalComment,
        CommentInterface $updatedComment
    ):bool {
        return (bool)$updatedComment->getReplyToCommentId() && $this->isPublished($originalComment, $updatedComment);
    }

    /**
     * Is reply comment
     *
     * @param int $commentId
     * @return bool
     */
    public function isReplyComment(int $commentId): bool
    {
        try {
            $comment = $this->commentRepository->getById($commentId);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return (bool)$comment->getReplyToCommentId();
    }
}
