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

abstract class AbstractDataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        protected readonly ArrayManager $arrayManager
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
        $dataProviderPath = 'components/awBlogEmailSubscriberNotificationFormProvider';
        $jsLayout = $this->arrayManager->merge(
            $dataProviderPath,
            $jsLayout,
            [
                'data' => $this->getProviderData($relatedObjectList)
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve provider data array
     *
     * @param array $relatedObjectList
     * @return array
     */
    abstract protected function getProviderData(array $relatedObjectList): array;
}
