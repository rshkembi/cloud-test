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

namespace Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier\TemplateVariables\Comment\Post;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Email\Metadata\Builder\ModifierInterface;
use Aheadworks\Blog\Model\Email\MetadataInterface as EmailMetadataInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Title implements ModifierInterface
{
    /**
     * @param CommentRepositoryInterface $commentRepository
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly PostRepositoryInterface $postRepository
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
        $templateVariables['post_title'] = $this->getPostTitle($emailQueueItem);
        $emailMetadata->setTemplateVariables($templateVariables);

        return $emailMetadata;
    }

    /**
     * Get product name
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function getPostTitle(EmailQueueItemInterface $emailQueueItem): string
    {
        $comment = $this->commentRepository->getById((int)$emailQueueItem->getObjectId());

        return $this->postRepository->get($comment->getPostId())->getTitle();
    }
}
