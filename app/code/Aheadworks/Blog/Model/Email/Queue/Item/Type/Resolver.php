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

namespace Aheadworks\Blog\Model\Email\Queue\Item\Type;

use Magento\Framework\Exception\ConfigurationMismatchException;

class Resolver
{
    /**
     * @param array $notificationGroupMap
     */
    public function __construct(
        private readonly array $notificationGroupMap = []
    ) {
    }

    /**
     * Retrieve list of email queue types for specific group of subscriber notification
     *
     * @param int $subscriberNotificationGroup
     * @return array
     * @throws ConfigurationMismatchException
     */
    public function getTypeListByNotificationGroup(int $subscriberNotificationGroup): array
    {
        if (isset($this->notificationGroupMap[$subscriberNotificationGroup])
            && is_array($this->notificationGroupMap[$subscriberNotificationGroup])
        ) {
            return $this->notificationGroupMap[$subscriberNotificationGroup];
        }

        throw new ConfigurationMismatchException(
            __('Unknown subscriber notification group: %1', $subscriberNotificationGroup)
        );
    }
}
