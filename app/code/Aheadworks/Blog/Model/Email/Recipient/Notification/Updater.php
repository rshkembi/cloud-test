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

namespace Aheadworks\Blog\Model\Email\Recipient\Notification;

use Aheadworks\Blog\Api\EmailRecipientManagementInterface;
use Aheadworks\Blog\Model\Email\Queue\Item\Type\Resolver as QueueItemTypeResolver;
use Aheadworks\Blog\Model\Source\Email\Subscriber\Notification\Group
    as SubscriberNotificationGroupSourceModel;
use Magento\Framework\Exception\LocalizedException;

class Updater
{
    /**
     * @param EmailRecipientManagementInterface $emailRecipientService
     * @param SubscriberNotificationGroupSourceModel $subscriberNotificationGroupSourceModel
     * @param QueueItemTypeResolver $queueItemTypeResolver
     */
    public function __construct(
        private readonly EmailRecipientManagementInterface $emailRecipientService,
        private readonly SubscriberNotificationGroupSourceModel $subscriberNotificationGroupSourceModel,
        private readonly QueueItemTypeResolver $queueItemTypeResolver
    ) {
    }

    /**
     * Update list of recipient notifications based on the selected group list
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int|null $websiteId
     * @param array $listOfNotificationGroupCustomerSubscribedTo
     * @return $this
     * @throws LocalizedException
     */
    public function updateRecipientSubscriptionList(
        ?int $customerId,
        ?string $recipientEmail,
        ?int $websiteId,
        array $listOfNotificationGroupCustomerSubscribedTo
    ):self {
        $subscriberNotificationGroupOptionArray = $this->subscriberNotificationGroupSourceModel->toOptionArray();

        foreach ($subscriberNotificationGroupOptionArray as $optionData) {
            if (isset($optionData['value'])) {
                $queueItemTypeList = $this->queueItemTypeResolver->getTypeListByNotificationGroup(
                    (int)$optionData['value']
                );
                if (in_array($optionData['value'], $listOfNotificationGroupCustomerSubscribedTo)) {
                    foreach ($queueItemTypeList as $queueItemType) {
                        $this->emailRecipientService->subscribeToNotification(
                            $customerId,
                            $recipientEmail,
                            $websiteId,
                            $queueItemType
                        );
                    }
                } else {
                    foreach ($queueItemTypeList as $queueItemType) {
                        $this->emailRecipientService->unsubscribeFromNotification(
                            $customerId,
                            $recipientEmail,
                            $websiteId,
                            $queueItemType
                        );
                    }
                }
            }
        }

        return $this;
    }
}
