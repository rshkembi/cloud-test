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

namespace Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier\TemplateVariables\Comment\Reply;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Model\Email\Metadata\Builder\ModifierInterface;
use Aheadworks\Blog\Model\Email\MetadataInterface as EmailMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Data implements ModifierInterface
{
    /**
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * Add metadata to existing object by connected queue item
     *
     * @param EmailMetadataInterface $emailMetadata
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailMetadataInterface
     * @throws LocalizedException
     */
    public function addMetadata(
        EmailMetadataInterface $emailMetadata,
        EmailQueueItemInterface $emailQueueItem
    ): EmailMetadataInterface {
        $templateVariables = $emailMetadata->getTemplateVariables();
        $replyComment = $this->getComment($emailQueueItem);
        $parentComment = $this->commentRepository->getById((int)$replyComment->getReplyToCommentId(), false);
        $templateVariables['comment_content'] = $parentComment->getContent();
        $templateVariables['reply_comment_content'] = $replyComment->getContent();
        $templateVariables['comment_author_name'] = $replyComment->getAuthorName();
        $templateVariables['comment_author_email'] = $replyComment->getAuthorEmail();

        $emailMetadata->setTemplateVariables($templateVariables);

        return $emailMetadata;
    }

    /**
     * Get comment by email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return CommentInterface
     * @throws NoSuchEntityException
     */
    private function getComment(EmailQueueItemInterface $emailQueueItem): CommentInterface
    {
        return $this->commentRepository->getById((int)$emailQueueItem->getObjectId());
    }
}
