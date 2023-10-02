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

namespace Aheadworks\Blog\Model\Email\Processor\Notification;

use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Model\Source\Email\Template as EmailTemplateSourceModel;
use Magento\Framework\Model\AbstractModel;

abstract class AbstractProcessor
{
    /**
     * @param EmailQueueManagementInterface $emailQueueService
     */
    public function __construct(
        protected EmailQueueManagementInterface $emailQueueService
    ) {
    }

    /**
     * Send notification about entity modification (creation) if necessary
     *
     * @param AbstractModel $updatedEntity
     * @param AbstractModel|null $originalEntity
     * @return $this
     */
    public function process(AbstractModel $updatedEntity, ?AbstractModel $originalEntity = null): self
    {
        if ($this->isNeedToNotifyRecipient($updatedEntity, $originalEntity)) {
            $storeIdToSendNotificationFrom = $this->getStoreIdToSendNotificationFrom($updatedEntity);
            $template = $this->getNotificationTemplate($storeIdToSendNotificationFrom);
            $recipientName = $this->getNotificationRecipientName(
                $updatedEntity,
                $storeIdToSendNotificationFrom
            );
            $recipientEmail = $this->getNotificationRecipientEmail(
                $updatedEntity,
                $storeIdToSendNotificationFrom
            );

            if (!empty($storeIdToSendNotificationFrom)
                && !empty($recipientName)
                && !empty($recipientEmail)
                && $template !== EmailTemplateSourceModel::DO_NOT_SEND_EMAIL_VALUE
            ) {
                $this->emailQueueService->addItem(
                    $this->getEmailQueueItemType(),
                    $this->getEntityId($updatedEntity),
                    $storeIdToSendNotificationFrom,
                    $recipientName,
                    $recipientEmail
                );
            }
        }

        return $this;
    }

    /**
     * Check is need to notify recipient about specific update of the entity
     *
     * @param AbstractModel $updatedEntity
     * @param AbstractModel|null $originalEntity
     * @return bool
     */
    abstract protected function isNeedToNotifyRecipient(
        AbstractModel $updatedEntity,
        ?AbstractModel $originalEntity = null
    ): bool;

    /**
     * Extract store id to send notification from
     *
     * @param AbstractModel $updatedEntity
     * @return int|null
     */
    abstract protected function getStoreIdToSendNotificationFrom(AbstractModel $updatedEntity): ?int;

    /**
     * Retrieve template for the specific notification
     *
     * @param int $storeIdToSendNotificationFrom
     * @return string
     */
    abstract protected function getNotificationTemplate(int $storeIdToSendNotificationFrom): string;

    /**
     * Retrieve name for recipient of the specific notification
     *
     * @param AbstractModel $updatedEntity
     * @param int $storeIdToSendNotificationFrom
     * @return string|null
     */
    abstract protected function getNotificationRecipientName(
        AbstractModel $updatedEntity,
        int $storeIdToSendNotificationFrom
    ): ?string;

    /**
     * Retrieve email for recipient of the specific notification
     *
     * @param AbstractModel $updatedEntity
     * @param int $storeIdToSendNotificationFrom
     * @return string|null
     */
    abstract protected function getNotificationRecipientEmail(
        AbstractModel $updatedEntity,
        int $storeIdToSendNotificationFrom
    ): ?string;

    /**
     * Extract entity id, connected to the notification
     *
     * @param AbstractModel $updatedEntity
     * @return int
     */
    abstract protected function getEntityId(AbstractModel $updatedEntity): int;

    /**
     * Retrieve email queue item type for the specific notification
     *
     * @return string
     */
    abstract protected function getEmailQueueItemType(): string;
}
