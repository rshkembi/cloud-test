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

namespace Aheadworks\Blog\Model\Layout\Processor\Email\Subscriber\Notification;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Blog\Model\Source\Email\Subscriber\Notification\Group
    as EmailSubscriberNotificationGroupSourceModel;

class ConfigDataProvider implements LayoutProcessorInterface
{
    /**
     * @param EmailSubscriberNotificationGroupSourceModel $emailSubscriberNotificationGroupSourceModel
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private readonly EmailSubscriberNotificationGroupSourceModel $emailSubscriberNotificationGroupSourceModel,
        private readonly ArrayManager $arrayManager
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $configProviderPath = 'components/awBlogEmailSubscriberNotificationConfigProvider';
        $jsLayout = $this->arrayManager->merge(
            $configProviderPath,
            $jsLayout,
            [
                'data' => $this->getConfigData()
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve config data
     *
     * @return array
     */
    private function getConfigData(): array
    {
        return [
            'subscriber_notification_group_option_array' =>
                $this->emailSubscriberNotificationGroupSourceModel->toOptionArray(),
        ];
    }
}
