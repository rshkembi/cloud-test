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
 * Interface EmailQueueManagementInterface
 *
 * @package Aheadworks\Blog\Api
 * @api
 */
interface EmailQueueManagementInterface
{
    /**
     * Limit to send scheduled items per iteration
     */
    public const SEND_LIMIT = 100;

    /**
     * Add new email queue item
     *
     * @param string $type
     * @param int $objectId
     * @param int $storeId
     * @param string $recipientName
     * @param string $recipientEmail
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface|null
     */
    public function addItem(string $type, int $objectId, int $storeId, string $recipientName, string $recipientEmail);

    /**
     * Cancel email queue item
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cancelItem(\Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem);

    /**
     * Cancel email queue item by id
     *
     * @param int $emailQueueItemId
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cancelItemById(int $emailQueueItemId);

    /**
     * Send email queue item
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendItem(\Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem): bool;

    /**
     * Send email queue item by id
     *
     * @param int $emailQueueItemId
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function sendItemById(int $emailQueueItemId): bool;

    /**
     * Delete processed email queue items, return qty of deleted items
     *
     * @return int
     */
    public function deleteProcessedItems(): int;

    /**
     * Send scheduled email queue items, return qty of items sent
     *
     * @return int
     */
    public function sendScheduledItems(): int;
}
