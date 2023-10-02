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

namespace Aheadworks\Blog\Model\Email\Subscriber\DataRow;

use Aheadworks\Blog\Api\EmailSubscriberDataRowRepositoryInterface;
use Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow as ResourceModel;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface as Entity;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterfaceFactory as EntityFactory;
use Aheadworks\Blog\Model\Email\Subscriber\DataRow as EntityModel;
use Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow\Collection;
use Aheadworks\Blog\Model\ResourceModel\Email\Subscriber\DataRow\CollectionFactory;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowSearchResultsInterface as SearchResultsInterface;
use Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowSearchResultsInterfaceFactory
    as SearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchCriteriaInterface;

class Repository implements EmailSubscriberDataRowRepositoryInterface
{
    /**
     * @var array
     */
    private array $instanceList = [];

    /**
     * @param ResourceModel $resourceModel
     * @param EntityFactory $entityFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        private readonly ResourceModel $resourceModel,
        private readonly EntityFactory $entityFactory,
        private readonly CollectionFactory $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly DataObjectHelper $dataObjectHelper
    ) {
    }

    /**
     * Retrieve subscriber data row by id
     *
     * @param int $entityId
     * @return Entity
     * @throws NoSuchEntityException
     */
    public function getById(int $entityId)
    {
        if (!isset($this->instanceList[$entityId])) {
            /** @var Entity $entity */
            $entity = $this->entityFactory->create();
            $this->resourceModel->load($entity, $entityId);
            if (!$entity->getEntityId()) {
                throw NoSuchEntityException::singleField(
                    Entity::ENTITY_ID,
                    $entityId
                );
            }
            $this->instanceList[$entityId] = $entity;
        }
        return $this->instanceList[$entityId];
    }

    /**
     * Save subscriber data row
     *
     * @param Entity $entity
     * @return Entity
     * @throws CouldNotSaveException
     */
    public function save(Entity $entity)
    {
        try {
            $this->resourceModel->save($entity);
            $this->instanceList[$entity->getEntityId()] = $entity;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $entity;
    }

    /**
     * Delete subscriber data row
     *
     * @param Entity $entity
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Entity $entity): bool
    {
        try {
            $this->resourceModel->delete($entity);
            if (isset($this->instanceList[$entity->getEntityId()])) {
                unset($this->instanceList[$entity->getEntityId()]);
            }
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }

        return true;
    }

    /**
     * Delete subscriber data row by id
     *
     * @param int $entityId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $entityId): bool
    {
        return $this->delete($this->getById($entityId));
    }

    /**
     * Retrieve subscriber data row list matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();

        $this->extensionAttributesJoinProcessor->process($collection, Entity::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var SearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $entityList = [];
        /** @var EntityModel $item */
        foreach ($collection->getItems() as $item) {
            $entityList[] = $this->getEntity($item);
        }
        $searchResults->setItems($entityList);

        return $searchResults;
    }

    /**
     * Retrieves entity using model
     *
     * @param EntityModel $model
     * @return Entity
     */
    private function getEntity(EntityModel $model): Entity
    {
        /** @var Entity $object */
        $entity = $this->entityFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $entity,
            $model->getData(),
            Entity::class
        );

        return $entity;
    }
}
