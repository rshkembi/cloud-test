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
namespace Aheadworks\Blog\Model\Category;

use Aheadworks\Blog\Api\CategoryManagementInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Magento\Framework\Api\SortOrderBuilder;

/**
 * Class Management
 * @package Aheadworks\Blog\Model\Category
 */
class Management implements CategoryManagementInterface
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
    }

    /**
     * @inheridoc
     */
    public function getChildCategories($categoryId, $storeId, $status)
    {
        if (!is_array($status)) {
            $status = [$status];
        }
        $allowedStores =  [$storeId, \Magento\Store\Model\Store::DEFAULT_STORE_ID];

        /** @var SortOrder $order */
        $order = $this->sortOrderBuilder
            ->setField(CategoryInterface::SORT_ORDER)
            ->setAscendingDirection()
            ->create();

        $this->searchCriteriaBuilder
            ->addFilter(CategoryInterface::STATUS, $status, 'in')
            ->addFilter(CategoryInterface::STORE_IDS, $allowedStores, 'in')
            ->addFilter(CategoryInterface::PARENT_ID, $categoryId)
            ->addSortOrder($order);

        return $this->categoryRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
