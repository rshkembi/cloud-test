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

namespace Aheadworks\Blog\Model\Email\Metadata\Builder\Modifier;

use Aheadworks\Blog\Model\Email\Metadata\Builder\ModifierInterface;
use Magento\Framework\Exception\ConfigurationMismatchException;

class Pool
{
    /**
     * @param ModifierInterface[] $modifierList
     */
    public function __construct(
        private readonly array $modifierList = []
    ) {
    }

    /**
     * Retrieve metadata modifier for specific queue item type
     *
     * @param string $emailQueueItemType
     * @return ModifierInterface
     * @throws ConfigurationMismatchException
     */
    public function getModifierByEmailQueueItemType(string $emailQueueItemType): ModifierInterface
    {
        if (!isset($this->modifierList[$emailQueueItemType])) {
            throw new ConfigurationMismatchException(
                __('Unknown email metadata modifier for queue item: %1 requested', ModifierInterface::class)
            );
        }

        return $this->modifierList[$emailQueueItemType];
    }
}
