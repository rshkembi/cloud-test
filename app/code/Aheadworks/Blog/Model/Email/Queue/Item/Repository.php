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

use Aheadworks\Blog\Api\EmailQueueItemRepositoryInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterfaceFactory as EmailQueueItemInterfaceFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Aheadworks\Blog\Model\Email\Queue\Item as EmailQueueItem;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item as EmailQueueItemResourceModel;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item\Collection as EmailQueueItemCollection;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item\CollectionFactory as EmailQueueItemCollectionFactory;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemSearchResultsInterface as EmailQueueItemSearchResultsInterface;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemSearchResultsInterfaceFactory
    as EmailQueueItemSearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;

class Repository implements EmailQueueItemRepositoryInterface
{
    /**
     * @var array
     */
    private array $emailQueueItemInstances = [];

    /**
     * @param EmailQueueItemResourceModel $resource
     * @param EmailQueueItemInterfaceFactory $emailQueueItemInterfaceFactory
     * @param EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory
     * @param EmailQueueItemSearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        private readonly EmailQueueItemResourceModel $resource,
        private readonly EmailQueueItemInterfaceFactory $emailQueueItemInterfaceFactory,
        private readonly EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory,
        private readonly EmailQueueItemSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly DataObjectHelper $dataObjectHelper
    ) {
    }

    /**
     * Retrieve email queue item by id
     *
     * @param int $emailQueueItemId
     * @return EmailQueueItemInterface
     * @throws NoSuchEntityException
     */
    public function getById($emailQueueItemId)
    {
        if (!isset($this->emailQueueItemInstances[$emailQueueItemId])) {
            /** @var EmailQueueItemInterface $emailQueueItem */
            $emailQueueItem = $this->emailQueueItemInterfaceFactory->create();
            $this->resource->load($emailQueueItem, $emailQueueItemId);
            if (!$emailQueueItem->getEntityId()) {
                throw NoSuchEntityException::singleField(
                    EmailQueueItemInterface::ENTITY_ID,
                    $emailQueueItemId
                );
            }
            $this->emailQueueItemInstances[$emailQueueItemId] = $emailQueueItem;
        }

        return $this->emailQueueItemInstances[$emailQueueItemId];
    }

    /**
     * Save email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return EmailQueueItemInterface
     * @throws CouldNotSaveException
     */
    public function save(EmailQueueItemInterface $emailQueueItem)
    {
        try {
            $this->resource->save($emailQueueItem);
            $this->emailQueueItemInstances[$emailQueueItem->getEntityId()] = $emailQueueItem;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $emailQueueItem;
    }

    /**
     * Delete email queue item
     *
     * @param EmailQueueItemInterface $emailQueueItem
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(EmailQueueItemInterface $emailQueueItem)
    {
        try {
            $this->resource->delete($emailQueueItem);
            unset($this->emailQueueItemInstances[$emailQueueItem->getEntityId()]);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete email queue item by id
     *
     * @param int $emailQueueItemId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($emailQueueItemId)
    {
        return $this->delete($this->getById($emailQueueItemId));
    }

    /**
     * Retrieve email queue item list matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return EmailQueueItemSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var EmailQueueItemCollection $collection */
        $collection = $this->emailQueueItemCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, EmailQueueItemInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var EmailQueueItemSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var EmailQueueItem $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * Retrieves data object using model
     *
     * @param EmailQueueItem $model
     * @return EmailQueueItemInterface
     */
    private function getDataObject(EmailQueueItem $model): EmailQueueItemInterface
    {
        /** @var EmailQueueItemInterface $object */
        $object = $this->emailQueueItemInterfaceFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $object,
            $model->getData(),
            EmailQueueItemInterface::class
        );

        return $object;
    }
}
