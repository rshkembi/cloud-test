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
namespace Aheadworks\Blog\Model\ResourceModel;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterfaceFactory;
use Aheadworks\Blog\Api\Data\CategorySearchResultsInterfaceFactory;
use Aheadworks\Blog\Model\CategoryFactory;
use Aheadworks\Blog\Model\CategoryRegistry;
use Aheadworks\Blog\Model\ResourceModel\Category as ResourceCategory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Category repository
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CategoryRepository implements \Aheadworks\Blog\Api\CategoryRepositoryInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @var CategoryInterfaceFactory
     */
    private $categoryDataFactory;

    /**
     * @var CategoryRegistry
     */
    private $categoryRegistry;

    /**
     * @var CategorySearchResultsInterfaceFactory
     */
    private $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;

    /**
     * @var JoinProcessorInterface
     */
    private $extensionAttributesJoinProcessor;

    /**
     * @var ResourceCategory
     */
    private $categoryResource;

    /**
     * @param EntityManager $entityManager
     * @param CategoryFactory $categoryFactory
     * @param CategoryInterfaceFactory $categoryDataFactory
     * @param CategoryRegistry $categoryRegistry
     * @param CategorySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param Category $categoryResource
     */
    public function __construct(
        EntityManager $entityManager,
        CategoryFactory $categoryFactory,
        CategoryInterfaceFactory $categoryDataFactory,
        CategoryRegistry $categoryRegistry,
        CategorySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ResourceCategory $categoryResource
    ) {
        $this->entityManager = $entityManager;
        $this->categoryFactory = $categoryFactory;
        $this->categoryDataFactory = $categoryDataFactory;
        $this->categoryRegistry = $categoryRegistry;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->categoryResource = $categoryResource;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CategoryInterface $category)
    {
        /** @var \Aheadworks\Blog\Model\Category $categoryModel */
        $categoryModel = $this->categoryFactory->create();
        if ($categoryId = $category->getId()) {
            $this->entityManager->load($categoryModel, $categoryId);
        }
        $this->dataObjectHelper->populateWithArray(
            $categoryModel,
            $this->dataObjectProcessor->buildOutputDataArray($category, CategoryInterface::class),
            CategoryInterface::class
        );

        $this->entityManager->save($categoryModel);
        $category = $this->getCategoryDataObject($categoryModel);
        $this->categoryRegistry->push($category);
        return $category;
    }

    /**
     * {@inheritdoc}
     */
    public function get($categoryId)
    {
        return $this->categoryRegistry->retrieve($categoryId);
    }

    /**
     * {@inheritdoc}
     */
    public function getByUrlKey($categoryUrlKey)
    {
        return $this->categoryRegistry->retrieveByUrl($categoryUrlKey);
    }

    /**
     * {@inheritdoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Aheadworks\Blog\Api\Data\CategorySearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create()
            ->setSearchCriteria($searchCriteria);
        /** @var \Aheadworks\Blog\Model\ResourceModel\Category\Collection $collection */
        $collection = $this->categoryFactory->create()->getCollection();
        $this->extensionAttributesJoinProcessor->process($collection, CategoryInterface::class);
        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() == CategoryInterface::STORE_IDS) {
                    $collection->addStoreFilter($filter->getValue());
                } else {
                    $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                    $fields[] = $filter->getField();
                    $conditions[] = [$condition => $filter->getValue()];
                }
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        if ($sortOrders = $searchCriteria->getSortOrders()) {
            /** @var \Magento\Framework\Api\SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder($sortOrder->getField(), $sortOrder->getDirection());
            }
        }

        $collection
            ->setCurPage($searchCriteria->getCurrentPage())
            ->setPageSize($searchCriteria->getPageSize());

        $categories = [];
        /** @var \Aheadworks\Blog\Model\Category $categoryModel */
        foreach ($collection as $categoryModel) {
            $categories[] = $this->getCategoryDataObject($categoryModel);
        }
        $searchResults->setItems($categories);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(CategoryInterface $category)
    {
        return $this->deleteById($category->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($categoryId)
    {
        /** @var \Aheadworks\Blog\Model\Category $categoryModel */
        $categoryModel = $this->categoryFactory->create();
        $this->entityManager->load($categoryModel, $categoryId);
        if (!$categoryModel->getId()) {
            throw NoSuchEntityException::singleField('categoryId', $categoryId);
        }
        $this->removeAllChild($categoryId);
        $this->entityManager->delete($categoryModel);
        $this->categoryRegistry->remove($categoryId);
        return true;
    }

    /**
     * Creates category data object using Category Model
     *
     * @param \Aheadworks\Blog\Model\Category $category
     * @return CategoryInterface
     */
    private function getCategoryDataObject(\Aheadworks\Blog\Model\Category $category)
    {
        /** @var CategoryInterface $categoryDataObject */
        $categoryDataObject = $this->categoryDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $categoryDataObject,
            $category->getData(),
            CategoryInterface::class
        );
        return $categoryDataObject;
    }

    /**
     * Removes all child categories from parent
     *
     * @param int $categoryId
     * @throws NoSuchEntityException
     */
    private function removeAllChild($categoryId)
    {
        $categoryModel = $this->categoryFactory->create();
        $childCategories = $this->categoryResource->getAllChildCategories($categoryId);
        if (isset($childCategories) && count($childCategories)) {
            foreach ($childCategories as $childCategory) {
                $childCategoryId = $childCategory['id'];
                $this->entityManager->load($categoryModel, $childCategoryId);
                if (!$categoryModel->getId()) {
                    throw NoSuchEntityException::singleField('categoryId', $childCategoryId);
                }
                $this->entityManager->delete($categoryModel);
                $this->categoryRegistry->remove($childCategoryId);
            }
        }
    }
}
