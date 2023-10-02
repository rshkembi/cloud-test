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

namespace Aheadworks\Blog\Model\Data\Operation\Email\Queue\Item;

use Aheadworks\Blog\Model\Data\OperationInterface;
use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Magento\Framework\Exception\LocalizedException;

class Send implements OperationInterface
{
    /**
     * @param EmailQueueManagementInterface $emailQueueService
     */
    public function __construct(
        private readonly EmailQueueManagementInterface $emailQueueService
    ) {
    }

    /**
     * Send queue item operation
     *
     * @param array $entityData
     * @return bool
     * @throws LocalizedException
     */
    public function execute(array $entityData): bool
    {
        $emailQueueItemId = $entityData[EmailQueueItemInterface::ENTITY_ID] ?? null;

        return $this->emailQueueService->sendItemById((int)$emailQueueItemId);
    }
}
