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

namespace Aheadworks\Blog\Model\Layout\Processor\Email\Subscriber\Notification\DataProvider;

use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface as EmailSubscriberDataRowInterface;
use Aheadworks\Blog\Model\Email\Queue\Item\Provider as EmailQueueItemProvider;
use Aheadworks\Blog\Model\Email\Queue\Item\SecurityCode\Checker as SecurityCodeChecker;
use Aheadworks\Blog\Model\Email\Recipient\Notification\Provider as RecipientNotificationProvider;
use Aheadworks\Blog\Model\Layout\Processor\Email\Subscriber\Notification\AbstractDataProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\ArrayManager;

class SecurityCode extends AbstractDataProvider
{
    /**
     * @param ArrayManager $arrayManager
     * @param SecurityCodeChecker $securityCodeChecker
     * @param EmailQueueItemProvider $emailQueueItemProvider
     * @param RecipientNotificationProvider $recipientNotificationProvider
     */
    public function __construct(
        ArrayManager $arrayManager,
        private readonly SecurityCodeChecker $securityCodeChecker,
        private readonly EmailQueueItemProvider $emailQueueItemProvider,
        private readonly RecipientNotificationProvider $recipientNotificationProvider
    ) {
        parent::__construct($arrayManager);
    }

    /**
     * Retrieve provider data array
     *
     * @param array $relatedObjectList
     * @return array
     * @throws LocalizedException
     */
    protected function getProviderData(array $relatedObjectList): array
    {
        $store = $relatedObjectList[self::STORE_KEY] ?? null;
        $securityCode = $relatedObjectList[self::SECURITY_CODE] ?? '';
        $emailQueueItem = $this->emailQueueItemProvider->getItemBySecurityCode($securityCode);
        if ($store
            && $emailQueueItem
            && $this->securityCodeChecker->isValid($securityCode)
        ) {
            return [
                'subscriber_notification_group' =>
                    $this->recipientNotificationProvider->getListOfGroupRecipientSubscribedTo(
                        null,
                        $emailQueueItem->getRecipientEmail(),
                        (int)$store->getWebsiteId()
                    ),
                EmailSubscriberDataRowInterface::WEBSITE_ID => (int)$store->getWebsiteId(),
                EmailSubscriberDataRowInterface::CUSTOMER_EMAIL => $emailQueueItem->getRecipientEmail(),
            ];
        }

        throw new LocalizedException(
            __('Unsubscribe link has already expired.')
        );
    }
}
