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

namespace Aheadworks\Blog\Model\Email\Queue\Item;

use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\EmailQueueItemRepositoryInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Email\Queue\DateTime\Resolver as EmailQueueDateTimeResolver;
use Aheadworks\Blog\Model\Source\Email\Queue\Item\Status as EmailQueueItemStatusSourceModel;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Provider
{
    /**
     * @param EmailQueueItemRepositoryInterface $emailQueueItemRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param EmailQueueDateTimeResolver $emailQueueDateTimeResolver
     * @param Config $config
     * @param EmailQueueItemStatusSourceModel $emailQueueItemStatusSourceModel
     */
    public function __construct(
        private readonly EmailQueueItemRepositoryInterface $emailQueueItemRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly EmailQueueDateTimeResolver $emailQueueDateTimeResolver,
        private readonly Config $config,
        private readonly EmailQueueItemStatusSourceModel $emailQueueItemStatusSourceModel
    ) {
    }

    /**
     * Retrieve item by its security code
     *
     * @param string $securityCode
     * @return EmailQueueItemInterface|null
     */
    public function getItemBySecurityCode(string $securityCode): ?EmailQueueItemInterface
    {
        $item = null;

        $this->searchCriteriaBuilder
            ->addFilter(
                EmailQueueItemInterface::SECURITY_CODE,
                $securityCode,
                'eq'
            )->setPageSize(1);
        $itemList = $this->emailQueueItemRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        if (count($itemList) > 0) {
            $item = reset($itemList);
        }

        return $item;
    }

    /**
     * Retrieve list of processed items, that has to be removed according to storage settings
     *
     * @return EmailQueueItemInterface[]
     */
    public function getProcessedItemListToDelete(): array
    {
        $itemList = [];

        $storageTimeInDays = $this->config->getNotificationStorageTimeInDays();
        if ($storageTimeInDays > 0) {
            $deadlineDateTime = $this->emailQueueDateTimeResolver->getDeadlineDateTimeInDbFormatForProcessedEmails(
                $storageTimeInDays
            );

            $this->searchCriteriaBuilder
                ->addFilter(
                    EmailQueueItemInterface::STATUS,
                    $this->emailQueueItemStatusSourceModel->getProcessedStatuses(),
                    'in'
                )->addFilter(
                    EmailQueueItemInterface::SCHEDULED_AT,
                    $deadlineDateTime,
                    'lteq'
                )
            ;

            $itemList = $this->emailQueueItemRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        }

        return $itemList;
    }

    /**
     * Retrieve list of scheduled items, that has to be sent
     *
     * @param int $count
     * @return EmailQueueItemInterface[]|null
     */
    public function getScheduledItemListToSend(int $count): ?array
    {
        $currentDateTime = $this->emailQueueDateTimeResolver->getCurrentDateTimeInDbFormat();

        $this->searchCriteriaBuilder
            ->addFilter(
                EmailQueueItemInterface::STATUS,
                $this->emailQueueItemStatusSourceModel->getUnprocessedStatuses(),
                'in'
            )->addFilter(
                EmailQueueItemInterface::SCHEDULED_AT,
                $currentDateTime,
                'lteq'
            )->setPageSize($count)
        ;

        return $this->emailQueueItemRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
