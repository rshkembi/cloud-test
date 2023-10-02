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
namespace Aheadworks\Blog\Model\Category\Listing;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Blog\Api\CategoryRepositoryInterface as CategoryRepository;

/**
 * Class Builder
 * @package Aheadworks\Blog\Model\Category\Listing
 */
class Builder
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    /**
     * Builder constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CategoryRepository $categoryRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Returns categories list
     *
     * @return \Aheadworks\Blog\Api\Data\CategoryInterface[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCategoryList()
    {
        return $this->categoryRepository
            ->getList($this->buildSearchCriteria())
            ->getItems();
    }

    /**
     * Retrieves search criteria builder
     *
     * @return SearchCriteriaBuilder
     */
    public function getSearchCriteriaBuilder()
    {
        return $this->searchCriteriaBuilder;
    }

    /**
     * Build search criteria
     *
     * @return \Magento\Framework\Api\SearchCriteria
     */
    private function buildSearchCriteria()
    {
        return $this->searchCriteriaBuilder->create();
    }
}
