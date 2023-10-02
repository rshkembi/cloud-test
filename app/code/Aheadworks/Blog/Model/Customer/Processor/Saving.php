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

namespace Aheadworks\Blog\Model\Customer\Processor;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Model\Email\Recipient\Notification\Updater
    as EmailRecipientNotificationUpdater;
use Psr\Log\LoggerInterface as Logger;
use Aheadworks\Blog\Model\Customer\Resolver as CustomerResolver;

class Saving
{
    /**#@+
     * Constants defined for fetching data from the request
     */
    public const AW_BLOG_CUSTOMER_FIELDSET_NAME = 'aw_blog_customer_section';
    public const AW_BLOG_CUSTOMER_NOTIFICATION_SETTINGS_DATA_KEY = 'customer_notification_settings';
    public const AW_BLOG_SUBSCRIBER_NOTIFICATION_GROUP_LIST_DATA_KEY = 'subscriber_notification_group';
    /**#@-*/

    /**
     * @param EmailRecipientNotificationUpdater $emailRecipientNotificationUpdater
     * @param Logger $logger
     * @param CustomerResolver $customerResolver
     */
    public function __construct(
        private readonly EmailRecipientNotificationUpdater $emailRecipientNotificationUpdater,
        private readonly Logger $logger,
        private readonly CustomerResolver $customerResolver
    ) {
    }

    /**
     * Process modification of subscriber notification settings for specific customer
     *
     * @param CustomerInterface $customer
     * @param RequestInterface $request
     * @return $this
     */
    public function processSubscriberNotificationDataModification(
        CustomerInterface $customer,
        RequestInterface $request
    ): self {
        try {
            $awBlogCustomerSectionData = $request->getParam(
                self::AW_BLOG_CUSTOMER_FIELDSET_NAME,
                []
            );
            $customerNotificationSettings =
                $awBlogCustomerSectionData[self::AW_BLOG_CUSTOMER_NOTIFICATION_SETTINGS_DATA_KEY] ?? [];
            $listOfNotificationGroupCustomerSubscribedTo =
                $customerNotificationSettings[self::AW_BLOG_SUBSCRIBER_NOTIFICATION_GROUP_LIST_DATA_KEY] ?? [];
            $listOfNotificationGroupCustomerSubscribedTo = is_array($listOfNotificationGroupCustomerSubscribedTo)
                ? $listOfNotificationGroupCustomerSubscribedTo : [];
            $this->emailRecipientNotificationUpdater->updateRecipientSubscriptionList(
                (int)$customer->getId(),
                null,
                $this->customerResolver->getDefaultWebsiteIdForCustomer($customer),
                $listOfNotificationGroupCustomerSubscribedTo
            );
        } catch (\Exception $exception) {
            $this->logger->warning($exception->getMessage());
        }

        return $this;
    }
}
