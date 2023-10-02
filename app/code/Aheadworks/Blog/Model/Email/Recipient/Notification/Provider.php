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

use Aheadworks\Blog\Model\Email\Queue\Item\Type\Resolver as QueueItemTypeResolver;
use Aheadworks\Blog\Model\Source\Email\Subscriber\Notification\Group
    as SubscriberNotificationGroupSourceModel;
use Aheadworks\Blog\Api\EmailRecipientManagementInterface;

class Provider
{
    /**
     * @param QueueItemTypeResolver $queueItemTypeResolver
     * @param EmailRecipientManagementInterface $emailRecipientService
     * @param SubscriberNotificationGroupSourceModel $subscriberNotificationGroupSourceModel
     */
    public function __construct(
        private readonly QueueItemTypeResolver $queueItemTypeResolver,
        private readonly EmailRecipientManagementInterface $emailRecipientService,
        private readonly SubscriberNotificationGroupSourceModel $subscriberNotificationGroupSourceModel
    ) {
    }

    /**
     * Retrieve list of notification groups that the email recipient is subscribed to
     *
     * @param int|null $customerId
     * @param string|null $recipientEmail
     * @param int $websiteId
     * @return array
     */
    public function getListOfGroupRecipientSubscribedTo(
        ?int $customerId,
        ?string $recipientEmail,
        int $websiteId
    ): array {
        $listOfGroupRecipientSubscribedTo = [];

        $subscriberNotificationGroupOptionArray = $this->subscriberNotificationGroupSourceModel->toOptionArray();

        foreach ($subscriberNotificationGroupOptionArray as $optionData) {
            if (isset($optionData['value'])) {
                try {
                    $queueItemTypeList = $this->queueItemTypeResolver->getTypeListByNotificationGroup(
                        (int)$optionData['value']
                    );
                    $isRecipientSubscribedToGroup = true;
                    foreach ($queueItemTypeList as $queueItemType) {
                        $isRecipientSubscribedToNotification = $this->emailRecipientService
                            ->isSubscribedToNotification(
                                $customerId,
                                $recipientEmail,
                                $websiteId,
                                $queueItemType
                            )
                        ;
                        $isRecipientSubscribedToGroup =
                            $isRecipientSubscribedToGroup
                            && $isRecipientSubscribedToNotification
                        ;
                        if (!$isRecipientSubscribedToGroup) {
                            break;
                        }
                    }
                    if ($isRecipientSubscribedToGroup) {
                        $listOfGroupRecipientSubscribedTo[] = $optionData['value'];
                    }
                } catch (\Exception $exception) {
                    continue;
                }
            }
        }

        return $listOfGroupRecipientSubscribedTo;
    }
}
