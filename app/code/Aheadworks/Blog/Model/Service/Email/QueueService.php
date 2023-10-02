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

namespace Aheadworks\Blog\Model\Service\Email;

use Aheadworks\Blog\Api\EmailQueueManagementInterface;
use Aheadworks\Blog\Api\EmailQueueItemRepositoryInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterfaceFactory as EmailQueueItemInterfaceFactory;
use Aheadworks\Blog\Model\Email\Queue\Item\SecurityCode\Generator as SecurityCodeGenerator;
use Magento\Framework\Exception\LocalizedException;
use Psr\Log\LoggerInterface as Logger;
use Aheadworks\Blog\Model\Source\Email\Queue\Item\Status as EmailQueueItemStatusSourceModel;
use Aheadworks\Blog\Model\Email\Queue\DateTime\Resolver as EmailQueueDateTimeResolver;
use Magento\Framework\Exception\CouldNotDeleteException;
use Aheadworks\Blog\Model\Email\Sender as EmailSender;
use Aheadworks\Blog\Model\Email\Metadata\Builder as EmailMetadataBuilder;
use Aheadworks\Blog\Model\Email\Queue\Item\Provider as EmailQueueItemProvider;
use Aheadworks\Blog\Model\Email\Queue\Item\Checker as EmailQueueItemChecker;

class QueueService implements EmailQueueManagementInterface
{
    /**
     * @param EmailQueueItemInterfaceFactory $emailQueueItemFactory
     * @param EmailQueueItemRepositoryInterface $emailQueueItemRepository
     * @param EmailQueueItemStatusSourceModel $emailQueueItemStatusSourceModel
     * @param EmailQueueDateTimeResolver $emailQueueDateTimeResolver
     * @param EmailSender $emailSender
     * @param EmailMetadataBuilder $emailMetadataBuilder
     * @param Logger $logger
     * @param EmailQueueItemProvider $emailQueueItemProvider
     * @param EmailQueueItemChecker $emailQueueItemChecker
     * @param SecurityCodeGenerator $securityCodeGenerator
     */
    public function __construct(
        private readonly EmailQueueItemInterfaceFactory $emailQueueItemFactory,
        private readonly EmailQueueItemRepositoryInterface $emailQueueItemRepository,
        private readonly EmailQueueItemStatusSourceModel $emailQueueItemStatusSourceModel,
        private readonly EmailQueueDateTimeResolver $emailQueueDateTimeResolver,
        private readonly EmailSender $emailSender,
        private readonly EmailMetadataBuilder $emailMetadataBuilder,
        private readonly Logger $logger,
        private readonly EmailQueueItemProvider $emailQueueItemProvider,
        private readonly EmailQueueItemChecker $emailQueueItemChecker,
        private readonly SecurityCodeGenerator $securityCodeGenerator
    ) {
    }

    /**
     * Add new email queue item
     *
     * @param string $type
     * @param int $objectId
     * @param int $storeId
     * @param string $recipientName
     * @param string $recipientEmail
     * @return EmailQueueItemInterface|null
     */
    public function addItem(
        string $type,
        int    $objectId,
        int    $storeId,
        string $recipientName,
        string $recipientEmail
    ) {
        $addedItem = null;

        try {
            if (!$this->emailQueueItemChecker->isNeedToAdd($type, $recipientEmail, $storeId, $objectId)) {
                return $addedItem;
            }

            /** @var EmailQueueItemInterface $emailQueueItem */
            $emailQueueItem = $this->emailQueueItemFactory->create();
            $emailQueueItem
                ->setType($type)
                ->setObjectId($objectId)
                ->setStoreId($storeId)
                ->setRecipientName($recipientName)
                ->setRecipientEmail($recipientEmail)
                ->setStatus(
                    $this->emailQueueItemStatusSourceModel->getDefaultStatus()
                )->setCreatedAt(
                    $this->emailQueueDateTimeResolver->getCurrentDateTimeInDbFormat()
                )->setScheduledAt(
                    $this->emailQueueDateTimeResolver->getScheduledDateTimeInDbFormat()
                )->setSecurityCode(
                    $this->securityCodeGenerator->getCode()
                )
            ;

            $savedEmailQueueItem = $this->emailQueueItemRepository->save($emailQueueItem);
            $addedItem = $savedEmailQueueItem;
        } catch (LocalizedException $exception) {
            $this->logger->warning($exception->getMessage());
        }

        return $addedItem;
    }

    /**
     * Cancel email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailQueueItemInterface
     * @throws LocalizedException
     */
    public function cancelItem(EmailQueueItemInterface $emailQueueItem): EmailQueueItemInterface
    {
        $emailQueueItem->setStatus(EmailQueueItemStatusSourceModel::CANCELED);
        $emailQueueItem = $this->emailQueueItemRepository->save($emailQueueItem);

        return $emailQueueItem;
    }

    /**
     * Cancel email queue item by id
     *
     * @param int $emailQueueItemId
     * @return EmailQueueItemInterface
     * @throws LocalizedException
     */
    public function cancelItemById(int $emailQueueItemId): EmailQueueItemInterface
    {
        $emailQueueItem = $this->emailQueueItemRepository->getById($emailQueueItemId);

        return $this->cancelItem($emailQueueItem);
    }

    /**
     * Send email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return bool
     * @throws LocalizedException
     */
    public function sendItem(EmailQueueItemInterface $emailQueueItem): bool
    {
        try {
            if ($this->emailQueueItemChecker->isNeedToSend($emailQueueItem)) {
                $emailMetadata = $this->emailMetadataBuilder->buildForEmailQueueItem($emailQueueItem);
                $this->emailSender->send($emailMetadata);
                $emailQueueItem->setStatus(EmailQueueItemStatusSourceModel::SENT);
                $emailQueueItem->setSentAt($this->emailQueueDateTimeResolver->getCurrentDateTimeInDbFormat());
                $this->emailQueueItemRepository->save($emailQueueItem);

                return true;
            }

            $this->cancelItem($emailQueueItem);

            return false;
        } catch (LocalizedException $exception) {
            $this->logger->warning($exception->getMessage());
            $emailQueueItem->setStatus(EmailQueueItemStatusSourceModel::FAILED);
            $this->emailQueueItemRepository->save($emailQueueItem);

            return false;
        }
    }

    /**
     * Send email queue item by id
     *
     * @param int $emailQueueItemId
     * @return bool
     * @throws LocalizedException
     */
    public function sendItemById(int $emailQueueItemId): bool
    {
        $emailQueueItem = $this->emailQueueItemRepository->getById($emailQueueItemId);

        return $this->sendItem($emailQueueItem);
    }

    /**
     * Delete processed email queue items, return qty of deleted items
     *
     * @return int
     */
    public function deleteProcessedItems(): int
    {
        $processedItemListToDelete = $this->emailQueueItemProvider->getProcessedItemListToDelete();

        $qtyOfDeletedItems = 0;
        foreach ($processedItemListToDelete as $emailQueueItem) {
            try {
                $this->emailQueueItemRepository->delete($emailQueueItem);
                $qtyOfDeletedItems++;
            } catch (CouldNotDeleteException $exception) {
                $this->logger->warning($exception->getMessage());
            }
        }

        return $qtyOfDeletedItems;
    }

    /**
     * Send scheduled email queue items, return qty of items sent
     *
     * @return int
     */
    public function sendScheduledItems(): int
    {
        $scheduledItemListToSend = $this->emailQueueItemProvider->getScheduledItemListToSend(self::SEND_LIMIT);

        $qtyOfItemsSent = 0;
        foreach ($scheduledItemListToSend as $emailQueueItem) {
            try {
                $this->sendItem($emailQueueItem);
                $qtyOfItemsSent++;
            } catch (LocalizedException $exception) {
                $this->logger->warning($exception->getMessage());
            }
        }

        return $qtyOfItemsSent;
    }
}
