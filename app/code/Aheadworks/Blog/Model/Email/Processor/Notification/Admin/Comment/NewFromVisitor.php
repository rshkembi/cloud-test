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

namespace Aheadworks\Blog\Model\Email\Processor\Notification\Admin\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Email\Processor\Notification\AbstractProcessor;
use Aheadworks\Blog\Model\Source\Email\Queue\Item\Type as EmailQueueItemTypeSourceModel;
use Magento\Framework\Model\AbstractModel;

class NewFromVisitor extends AbstractProcessor
{
    /**
     * @param EmailQueueManagementInterface $emailQueueService
     * @param Config $config
     */
    public function __construct(
        EmailQueueManagementInterface $emailQueueService,
        private readonly Config $config,
    ) {
        parent::__construct($emailQueueService);
    }

    /**
     * Check is need to notify recipient about specific update of the entity
     *
     * @param CommentInterface $updatedEntity
     * @param AbstractModel|null $originalEntity
     * @return bool
     */
    protected function isNeedToNotifyRecipient($updatedEntity, $originalEntity = null): bool
    {
        return !empty($this->config->getNotificationAdminRecipientEmail((int)$updatedEntity->getStoreId()));
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
        return $this->config->getNotificationTemplateForAdminAboutNewCommentFromVisitor(
            $storeIdToSendNotificationFrom
        ) ?? '';
    }

    /**
     * Retrieve name for recipient of the specific notification
     *
     * @param CommentInterface $updatedEntity
     */
    protected function getNotificationRecipientName($updatedEntity, $storeIdToSendNotificationFrom): ?string
    {
        return $this->config->getNotificationDefaultAdminRecipientName();
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
        return $this->config->getNotificationAdminRecipientEmail($storeIdToSendNotificationFrom);
    }

    /**
     * Extract entity id, connected to the notification
     *
     * @param CommentInterface $updatedEntity
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
        return EmailQueueItemTypeSourceModel::ADMIN_NEW_COMMENT_FROM_VISITOR;
    }
}
