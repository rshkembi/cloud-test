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

namespace Aheadworks\Blog\Api;

/**
 * Interface EmailRecipientManagementInterface
 */
interface EmailRecipientManagementInterface
{
    /**
     * Check if email recipient is subscribed to the notification of specific type within separate website
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return bool
     */
    public function isSubscribedToNotification(
        ?int $customerId, ?string $recipientEmail, int $websiteId, string $emailQueueItemType): bool;

    /**
     * Subscribe email recipient to the notification of specific type within separate website
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function subscribeToNotification(
        ?int $customerId, ?string $recipientEmail, int $websiteId, string $emailQueueItemType): bool;

    /**
     * Unsubscribe email recipient from the notification of specific type within separate website
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function unsubscribeFromNotification(
        ?int $customerId, ?string $recipientEmail, int $websiteId, string $emailQueueItemType): bool;
}
