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

namespace Aheadworks\Blog\Model\Service\Email;

use Aheadworks\Blog\Api\EmailRecipientManagementInterface;
use Aheadworks\Blog\Model\Email\Subscriber\DataRow\Provider as EmailSubscriberDataRowProvider;
use Aheadworks\Blog\Model\Email\Subscriber\DataRow\Repository as EmailSubscriberDataRowRepositoryInterface;
use Magento\Framework\Exception\CouldNotSaveException;

class RecipientService implements EmailRecipientManagementInterface
{
    /**
     * @param EmailSubscriberDataRowProvider $emailSubscriberDataRowProvider
     * @param EmailSubscriberDataRowRepositoryInterface $emailSubscriberDataRowRepository
     */
    public function __construct(
        private readonly EmailSubscriberDataRowProvider $emailSubscriberDataRowProvider,
        private readonly EmailSubscriberDataRowRepositoryInterface $emailSubscriberDataRowRepository
    ) {
    }

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
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId,
        string $emailQueueItemType
    ): bool {
        $emailSubscriberDataRow = $this->emailSubscriberDataRowProvider->getDataRow(
            $customerId,
            $recipientEmail,
            $websiteId,
            $emailQueueItemType
        );

        return $emailSubscriberDataRow->getValue();
    }

    /**
     * Subscribe email recipient to the notification of specific type within separate website
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return bool
     * @throws CouldNotSaveException
     */
    public function subscribeToNotification(
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId,
        string $emailQueueItemType
    ): bool {
        $emailSubscriberDataRow = $this->emailSubscriberDataRowProvider->getDataRow(
            $customerId,
            $recipientEmail,
            $websiteId,
            $emailQueueItemType
        );
        $emailSubscriberDataRow->setValue(true);
        $this->emailSubscriberDataRowRepository->save($emailSubscriberDataRow);

        return true;
    }

    /**
     * Unsubscribe email recipient from the notification of specific type within separate website
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @param string $emailQueueItemType
     * @return bool
     * @throws CouldNotSaveException
     */
    public function unsubscribeFromNotification(
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId,
        string $emailQueueItemType
    ): bool {
        $emailSubscriberDataRow = $this->emailSubscriberDataRowProvider->getDataRow(
            $customerId,
            $recipientEmail,
            $websiteId,
            $emailQueueItemType
        );
        $emailSubscriberDataRow->setValue(false);
        $this->emailSubscriberDataRowRepository->save($emailSubscriberDataRow);

        return true;
    }
}
