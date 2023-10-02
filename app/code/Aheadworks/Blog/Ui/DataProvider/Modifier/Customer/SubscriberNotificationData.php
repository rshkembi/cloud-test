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

namespace Aheadworks\Blog\Ui\DataProvider\Modifier\Customer;

use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Aheadworks\Blog\Model\Email\Recipient\Notification\Provider
    as RecipientNotificationProvider;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Blog\Model\Customer\Resolver as CustomerResolver;

class SubscriberNotificationData implements ModifierInterface
{
    /**
     * Path to the subscriber notification data in the data array
     */
    public const SUBSCRIBER_NOTIFICATION_DATA_PATH
        = 'aw_blog_customer_section/customer_notification_settings/subscriber_notification_group';

    /**
     * @param ArrayManager $arrayManager
     * @param RecipientNotificationProvider $recipientNotificationProvider
     * @param CustomerResolver $customerResolver
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly RecipientNotificationProvider $recipientNotificationProvider,
        private readonly CustomerResolver $customerResolver
    ) {
    }

    /**
     * Modify meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Modify data
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data)
    {
        if (empty($data)) {
            return $data;
        }

        $customerIdList = $this->extractCustomerIdList($data);
        $customerSubscriptionMap = [];

        foreach ($customerIdList as $customerId) {
            $customerSubscriptionMap[$customerId] =
                $this->recipientNotificationProvider->getListOfGroupRecipientSubscribedTo(
                    $customerId,
                    null,
                    $this->customerResolver->getDefaultWebsiteIdForCustomerId($customerId)
                );
        }

        foreach ($data as $dataKey => $fieldData) {
            if (isset(
                $fieldData['customer']['entity_id'],
                $customerSubscriptionMap[$fieldData['customer']['entity_id']]
            )) {
                $customerId = $fieldData['customer']['entity_id'];
                $listOfGroupCustomerSubscribedTo = $customerSubscriptionMap[$customerId];
                $data = $this->arrayManager->set(
                    $dataKey . '/' . self::SUBSCRIBER_NOTIFICATION_DATA_PATH,
                    $data,
                    $listOfGroupCustomerSubscribedTo
                );
            }
        }

        return $data;
    }

    /**
     * Extract list of customer ids from data provider data array
     *
     * @param array $data
     * @return array
     */
    private function extractCustomerIdList(array $data): array
    {
        $customerIdList = [];

        foreach ($data as $fieldData) {
            if (isset($fieldData['customer']['entity_id'])) {
                $customerIdList[] = (int)$fieldData['customer']['entity_id'];
            }
        }

        return $customerIdList;
    }
}
