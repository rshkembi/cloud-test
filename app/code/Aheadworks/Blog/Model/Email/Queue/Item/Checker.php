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

namespace Aheadworks\Blog\Model\Email\Queue\Item;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Model\StoreResolver;
use Aheadworks\Blog\Api\EmailRecipientManagementInterface;

class Checker
{
    /**
     * @param StoreResolver $storeResolver
     * @param EmailRecipientManagementInterface $emailRecipientService
     * @param array $adminNotificationTypeList
     */
    public function __construct(
        private readonly StoreResolver $storeResolver,
        private readonly EmailRecipientManagementInterface $emailRecipientService,
        private readonly array $adminNotificationTypeList = []
    ) {
    }

    /**
     * Check if need to add to the queue notification of separate type for recipient with separate email
     *
     * @param string $emailQueueItemType
     * @param string $recipientEmail
     * @param int $storeId
     * @param int $commentId
     * @return bool
     */
    public function isNeedToAdd(string $emailQueueItemType, string $recipientEmail, int $storeId , int $commentId): bool
    {
        $isNeedToNotify = false;
        if (in_array($emailQueueItemType, $this->adminNotificationTypeList)) {
            return true;
        }
        try {
            $store = $this->storeResolver->getStore($storeId);
            if ($store) {
                $websiteId = (int)$store->getWebsiteId();
                $isNeedToNotify = $this->emailRecipientService->isSubscribedToNotification(
                    null,
                    $recipientEmail,
                    $websiteId,
                    $emailQueueItemType
                );
            }
        } catch (\Exception $exception) {
            $isNeedToNotify = false;
        }

        return $isNeedToNotify;
    }

    /**
     * Check if need to send specific email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return false
     */
    public function isNeedToSend(EmailQueueItemInterface $emailQueueItem)
    {
        return $this->isNeedToAdd(
            $emailQueueItem->getType(),
            $emailQueueItem->getRecipientEmail(),
            (int)$emailQueueItem->getStoreId(),
            (int)$emailQueueItem->getObjectId()
        );
    }
}
