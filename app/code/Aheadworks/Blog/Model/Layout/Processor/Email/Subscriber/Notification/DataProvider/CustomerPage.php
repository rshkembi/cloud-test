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
use Aheadworks\Blog\Model\Email\Recipient\Notification\Provider as RecipientNotificationProvider;
use Aheadworks\Blog\Model\Layout\Processor\Email\Subscriber\Notification\AbstractDataProvider;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\ArrayManager;

class CustomerPage extends AbstractDataProvider
{
    /**
     * @param ArrayManager $arrayManager
     * @param RecipientNotificationProvider $recipientNotificationProvider
     */
    public function __construct(
        ArrayManager $arrayManager,
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
        $providerData = [];
        $customer = $relatedObjectList[self::CUSTOMER_KEY] ?? null;
        $store = $relatedObjectList[self::STORE_KEY] ?? null;

        if ($customer
            && $store
        ) {
            $providerData = [
                'subscriber_notification_group' =>
                    $this->recipientNotificationProvider->getListOfGroupRecipientSubscribedTo(
                        (int)$customer->getId(),
                        null,
                        (int)$store->getWebsiteId()
                    ),
                EmailSubscriberDataRowInterface::CUSTOMER_ID => $customer->getId(),
                EmailSubscriberDataRowInterface::WEBSITE_ID => $store->getWebsiteId(),
            ];
        }

        return $providerData;
    }
}
