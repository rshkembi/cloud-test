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

namespace Aheadworks\Blog\Observer\Comment;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Model\Email\Processor\Notification\Subscriber\Comment\Reply\NewReply as SubscriberNewReplyCommentEmailProcessor;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Email\Processor\Notification\Subscriber\Comment\Published
    as SubscriberCommentPublishedEmailProcessor;
use Aheadworks\Blog\Model\Email\Processor\Notification\Subscriber\Comment\Rejected
    as SubscriberCommentRejectedEmailProcessor;
use Aheadworks\Blog\Model\Post\Comment\Checker as CommentChecker;
use Magento\Framework\Exception\NoSuchEntityException;

class UpdateAfter implements ObserverInterface
{
    /**
     * @param SubscriberCommentPublishedEmailProcessor $subscriberCommentPublishedEmailProcessor
     * @param SubscriberCommentRejectedEmailProcessor $subscriberCommentRejectedEmailProcessor
     * @param SubscriberNewReplyCommentEmailProcessor $subscriberNewReplyCommentEmailProcessor
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentChecker $commentChecker
     */
    public function __construct(
        private readonly SubscriberCommentPublishedEmailProcessor $subscriberCommentPublishedEmailProcessor,
        private readonly SubscriberCommentRejectedEmailProcessor $subscriberCommentRejectedEmailProcessor,
        private readonly SubscriberNewReplyCommentEmailProcessor $subscriberNewReplyCommentEmailProcessor,
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly CommentChecker $commentChecker
    ) {
    }

    /**
     * Notification after update comment
     *
     * @param Observer $observer
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $originalComment = $event->getData('original_comment');
        $updatedComment = $event->getData('updated_comment');
        if ($originalComment instanceof CommentInterface
            && $updatedComment instanceof CommentInterface
        ) {
            $this->subscriberCommentPublishedEmailProcessor->process(
                $updatedComment,
                $originalComment
            );
            if ($this->commentChecker->canSendOwnerCommentNotification($originalComment, $updatedComment)) {
                $parentComment = $this->commentRepository->getById($updatedComment->getReplyToCommentId());
                $updatedComment->setAuthorEmail($parentComment->getAuthorEmail());
                $updatedComment->setAuthorName($parentComment->getAuthorName());
                $this->subscriberNewReplyCommentEmailProcessor->process($updatedComment);
            }
            $this->subscriberCommentRejectedEmailProcessor->process(
                $updatedComment,
                $originalComment
            );
        }
    }
}
