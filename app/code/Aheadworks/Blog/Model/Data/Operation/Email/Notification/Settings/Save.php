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

namespace Aheadworks\Blog\Model\Data\Operation\Email\Notification\Settings;

use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface as EmailSubscriberDataRowInterface;
use Aheadworks\Blog\Model\Data\OperationInterface;
use Aheadworks\Blog\Model\Email\Recipient\Notification\Updater
    as EmailRecipientNotificationUpdater;
use Magento\Framework\Exception\LocalizedException;

class Save implements OperationInterface
{
    /**
     * @param EmailRecipientNotificationUpdater $emailRecipientNotificationUpdater
     */
    public function __construct(
        private readonly EmailRecipientNotificationUpdater $emailRecipientNotificationUpdater
    ) {
    }

    /**
     * Save subscriber notification settings data
     *
     * @param array $entityData
     * @return bool
     * @throws LocalizedException
     */
    public function execute(array $entityData)
    {
        $customerId = $entityData[EmailSubscriberDataRowInterface::CUSTOMER_ID] ?? null;
        $customerEmail = $entityData[EmailSubscriberDataRowInterface::CUSTOMER_EMAIL] ?? null;
        $websiteId =  $entityData[EmailSubscriberDataRowInterface::WEBSITE_ID] ?? null;
        $listOfNotificationGroupCustomerSubscribedTo =  $entityData['subscriber_notification_group'] ?? [];

        $this->emailRecipientNotificationUpdater->updateRecipientSubscriptionList(
            $customerId ? (int)$customerId : null,
            $customerEmail,
            $websiteId ? (int)$websiteId : null,
            $listOfNotificationGroupCustomerSubscribedTo
        );

        return true;
    }
}
