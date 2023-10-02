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

namespace Aheadworks\Blog\Model\Post;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\Data\CommentInterfaceFactory;
use Aheadworks\Blog\Api\Data\CommentSearchResultsInterface;
use Aheadworks\Blog\Api\Data\CommentSearchResultsInterfaceFactory;
use Aheadworks\Blog\Model\ResourceModel\Post\Comment as ResourceComment;
use Aheadworks\Blog\Model\ResourceModel\Post\Comment\Collection;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class CommentRepository
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @param CommentInterfaceFactory $commentFactory
     * @param CommentSearchResultsInterfaceFactory $searchResultsFactory
     * @param ResourceComment $resource
     * @param DataObjectHelper $dataObjectHelper
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param array $registryById
     */
    public function __construct(
        private readonly CommentInterfaceFactory $commentFactory,
        private readonly CommentSearchResultsInterfaceFactory $searchResultsFactory,
        private readonly ResourceComment $resource,
        private readonly DataObjectHelper $dataObjectHelper,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor,
        private readonly CollectionProcessorInterface $collectionProcessor,
        private array $registryById = []
    ) {
    }

    /**
     * Save comment
     *
     * @param CommentInterface $comment
     * @return CommentInterface
     * @throws CouldNotSaveException
     */
    public function save(CommentInterface $comment)
    {
        try {
            $this->resource->save($comment);
            $this->registryById[$comment->getId()] = $comment;
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }

        return $comment;
    }

    /**
     * Retrieve comment by id
     *
     * @param int $commentId
     * @param bool $isForceLoadEnabled
     * @return CommentInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $commentId, bool $isForceLoadEnabled = false)
    {
        if ($isForceLoadEnabled || !isset($this->registryById[$commentId])) {
            /** @var CommentInterface $comment */
            $comment = $this->commentFactory->create();
            $this->resource->load($comment, $commentId);
            if (!$comment->getId()) {
                throw NoSuchEntityException::singleField(CommentInterface::ID, $commentId);
            }
            $this->registryById[$comment->getId()] = $comment;
        }

        return $this->registryById[$commentId];
    }

    /**
     * Retrieve comments matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return CommentSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var Collection $collection */
        $collection = $this->commentFactory->create()->getCollection();

        $this->extensionAttributesJoinProcessor->process($collection, CommentInterface::class);
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount($collection->getSize());

        $objects = [];
        /** @var Comment $item */
        foreach ($collection->getItems() as $item) {
            $objects[] = $this->getDataObject($item);
        }
        $searchResults->setItems($objects);

        return $searchResults;
    }

    /**
     * Delete comment
     *
     * @param CommentInterface $comment
     * @return bool true on success
     * @throws CouldNotDeleteException
     */
    public function delete(CommentInterface $comment): bool
    {
        try {
            $this->resource->delete($comment);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        if (isset($this->registryById[$comment->getId()])) {
            unset($this->registryById[$comment->getId()]);
        }

        return true;
    }

    /**
     * Delete comment by ID
     *
     * @param int $commentId
     * @return bool true on success
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $commentId): bool
    {
        return $this->delete($this->getById($commentId));
    }

    /**
     * Retrieves comment object using comment model
     *
     * @param CommentInterface $comment
     * @return CommentInterface
     */
    private function getDataObject(CommentInterface $comment): CommentInterface
    {
        /** @var CommentInterface $commentObject */
        $commentObject = $this->commentFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $commentObject,
            $comment->getData(),
            CommentInterface::class
        );

        return $commentObject;
    }
}
