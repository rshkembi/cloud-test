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

namespace Aheadworks\Blog\Model\Email\Processor\Notification\Subscriber\Comment\Reply;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Email\Processor\Notification\AbstractProcessor;
use Aheadworks\Blog\Model\Source\Email\Queue\Item\Type as EmailQueueItemTypeSourceModel;

class NewReply extends AbstractProcessor
{
    /**
     * @param EmailQueueManagementInterface $emailQueueService
     * @param Config $config
     */
    public function __construct(
        EmailQueueManagementInterface $emailQueueService,
        private readonly Config $config
    ) {
        parent::__construct($emailQueueService);
    }

    /**
     * Check is need to notify recipient about specific update of the entity
     *
     * @param CommentInterface $updatedEntity
     * @param CommentInterface|null $originalEntity
     * @return bool
     */
    protected function isNeedToNotifyRecipient($updatedEntity, $originalEntity = null): bool
    {
        return (bool)$updatedEntity->getReplyToCommentId();
    }

    /**
     * Extract store id to send notification from
     *
     * @param CommentInterface $updatedEntity
     * @return int|null
     */
    protected function getStoreIdToSendNotificationFrom($updatedEntity): ?int
    {
        return (int)$updatedEntity->getStoreId();
    }

    /**
     * Retrieve template for the specific notification
     *
     * @param int $storeIdToSendNotificationFrom
     * @return string
     */
    protected function getNotificationTemplate(int $storeIdToSendNotificationFrom): string
    {
        return $this->config->getNotificationTemplateForAdminAboutNewReplyCommentFromVisitor(
            $storeIdToSendNotificationFrom
        ) ?? '';
    }

    /**
     * Retrieve name for recipient of the specific notification
     *
     * @param CommentInterface $updatedEntity
     * @param int $storeIdToSendNotificationFrom
     * @return string|null
     */
    protected function getNotificationRecipientName($updatedEntity, int $storeIdToSendNotificationFrom): ?string
    {
        return $updatedEntity->getAuthorName();
    }

    /**
     * Retrieve email for recipient of the specific notification
     *
     * @param CommentInterface $updatedEntity
     * @param int $storeIdToSendNotificationFrom
     * @return string|null
     */
    protected function getNotificationRecipientEmail($updatedEntity, int $storeIdToSendNotificationFrom): ?string
    {
        return $updatedEntity->getAuthorEmail();
    }

    /**
     * Extract entity id, connected to the notification
     *
     * @param CommentInterface $updatedEntity
     * @return int
     */
    protected function getEntityId($updatedEntity): int
    {
        return (int)$updatedEntity->getId();
    }

    /**
     * Retrieve email queue item type for the specific notification
     *
     * @return string
     */
    protected function getEmailQueueItemType(): string
    {
        return EmailQueueItemTypeSourceModel::SUBSCRIBER_REPLY_COMMENT;
    }
}
