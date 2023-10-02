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
use Aheadworks\Blog\Model\Source\Comment\Status;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Email\Processor\Notification\Admin\Comment\NewFromVisitor
    as AdminNewCommentFromVisitorEmailProcessor;
use Aheadworks\Blog\Model\Email\Processor\Notification\Admin\Comment\Reply\NewFromVisitor
    as AdminNewReplyCommentFromVisitorEmailProcessor;
use Aheadworks\Blog\Model\Email\Processor\Notification\Subscriber\Comment\Reply\NewReply
    as SubscriberNewReplyCommentEmailProcessor;
use Magento\Framework\Exception\NoSuchEntityException;

class CreationAfter implements ObserverInterface
{
    /**
     * @param AdminNewCommentFromVisitorEmailProcessor $adminNewCommentFromVisitorEmailProcessor
     * @param AdminNewReplyCommentFromVisitorEmailProcessor $adminNewReplyCommentFromVisitorEmailProcessor
     * @param SubscriberNewReplyCommentEmailProcessor $subscriberNewReplyCommentEmailProcessor
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        private readonly AdminNewCommentFromVisitorEmailProcessor $adminNewCommentFromVisitorEmailProcessor,
        private readonly AdminNewReplyCommentFromVisitorEmailProcessor $adminNewReplyCommentFromVisitorEmailProcessor,
        private readonly SubscriberNewReplyCommentEmailProcessor $subscriberNewReplyCommentEmailProcessor,
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * Notification after create comment
     *
     * @param Observer $observer
     * @return void
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $event = $observer->getEvent();
        $createdComment = $event->getData('created_comment');
        if ($createdComment instanceof CommentInterface) {
            if (!$createdComment->getReplyToCommentId()) {
                $this->adminNewCommentFromVisitorEmailProcessor->process($createdComment);
            } else {
                $this->adminNewReplyCommentFromVisitorEmailProcessor->process($createdComment);
                if ($createdComment->getStatus() === STATUS::APPROVE) {
                    $parentComment = $this->commentRepository->getById($createdComment->getReplyToCommentId());
                    $createdComment->setAuthorEmail($parentComment->getAuthorEmail());
                    $createdComment->setAuthorName($parentComment->getAuthorName());
                    $this->subscriberNewReplyCommentEmailProcessor->process($createdComment);
                }
            }
        }
    }
}
