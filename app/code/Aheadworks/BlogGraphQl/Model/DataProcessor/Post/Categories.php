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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model\DataProcessor\Post;

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Source\Category\Status as CategoryStatus;
use Aheadworks\BlogGraphQl\Model\DataProcessor\DataProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;

class Categories implements DataProcessorInterface
{
    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param DataObjectProcessor $dataObjectProcessor
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly DataObjectProcessor $dataObjectProcessor,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * Process data array before send
     *
     * @param array $data
     * @param array $args
     * @return array
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function process(array $data, array $args): array
    {
        /** @var PostInterface $post */
        $post = $data['model'];
        $storeId = $args['store_id'];
        $categoriesIds = [];
        foreach ($post->getCategoryIds() as $item) {
            if (!in_array($item, $categoriesIds)) {
                $categoriesIds[] = $item;
            }
        }
        $data = $this->addCategoriesToPost($categoriesIds, $data, $storeId);

        return $data;
    }

    /**
     * Add categories to blog post
     *
     * @param array $categoriesIds
     * @param array $post
     * @param int $storeId
     * @return array
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function addCategoriesToPost(array $categoriesIds, array $post, int $storeId): array
    {
        $this->searchCriteriaBuilder
            ->addFilter(CategoryInterface::STATUS, CategoryStatus::ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $storeId)
            ->addFilter(CategoryInterface::ID, $categoriesIds, 'in')
            ->addSortOrder(
                new SortOrder(
                    [
                        SortOrder::FIELD => CategoryInterface::NAME,
                        SortOrder::DIRECTION => SortOrder::SORT_ASC
                    ]
                )
            );
        $categories = [];
        $categoriesList = $this->categoryRepository->getList($this->searchCriteriaBuilder->create());
        foreach ($categoriesList->getItems() as $category) {
            $categories[$category->getId()] = $this->dataObjectProcessor->buildOutputDataArray($category, CategoryInterface::class);
        }

        foreach ($post['category_ids'] as $value) {
            if (array_key_exists($value, $categories)) {
                $post['categories']['items'][] = $categories[$value];
            }
        }

        return $post;
    }
}
