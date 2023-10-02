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
namespace Aheadworks\Blog\Block\Sidebar;

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Model\Source\Category\Status as CategoryStatus;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Blog\Api\CategoryManagementInterface;

/**
 * Category sidebar
 * @package Aheadworks\Blog\Block\Sidebar
 */
class Category extends Template implements IdentityInterface
{
    /**
     *  Additional css classes
     */
    const HIDE_CSS_CLASS = 'hide';
    const SHADED_CSS_CLASS = 'shaded';
    const SUBCATEGORY_CSS_CLASS = 'subcategory';

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var string
     */
    private $categoryDisplayLimit;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CategoryManagementInterface
     */
    private $categoryManager;

    /**
     * @param Context $context
     * @param CategoryRepositoryInterface $categoryRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Config $config
     * @param Url $url
     * @param CategoryManagementInterface $categoryManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        CategoryRepositoryInterface $categoryRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Config $config,
        Url $url,
        CategoryManagementInterface $categoryManager,
        array $data = []
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $context->getStoreManager();
        $this->config = $config;
        $this->url = $url;
        $this->categoryManager = $categoryManager;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve categories
     *
     * @return CategoryInterface[]
     */
    public function getChildCategories()
    {
        $currentCategoryId = $this->getRequest()->getParam('blog_category_id', null);
        $currentStoreId = $this->storeManager->getStore()->getId();

        return $this->categoryManager->getChildCategories(
            $currentCategoryId,
            $currentStoreId,
            CategoryStatus::ENABLED
        );
    }

    /**
     * Retrieve current category
     *
     * @return CategoryInterface|null
     */
    public function getCurrentCategory()
    {
        $currentCategoryId = $this->getRequest()->getParam('blog_category_id', null);

        return $this->getCategory($currentCategoryId);
    }

    /**
     * Retrieve parent category
     *
     * @return CategoryInterface|null
     */
    public function getParentCategory()
    {
        $currentCategory = $this->getCurrentCategory();
        $parentId = $currentCategory ? $currentCategory->getParentId() : null;

        return $this->getCategory($parentId);
    }

    /**
     * Retrieve category
     *
     * @param int|null $categoryId
     * @return CategoryInterface|null
     */
    private function getCategory($categoryId)
    {
        try {
            $category = $this->categoryRepository->get($categoryId);
        } catch (LocalizedException $e) {
            $category = null;
        }

        return $category;
    }

    /**
     * Get category limit for displaying
     *
     * @return int|null
     */
    public function getNumCategoriesToDisplay()
    {
        if ($this->categoryDisplayLimit === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $this->categoryDisplayLimit = $this->config->getNumCategoriesToDisplay($storeId);
        }
        return $this->categoryDisplayLimit;
    }

    /**
     * Retrieve category url
     *
     * @param CategoryInterface $category
     * @return string
     */
    public function getCategoryUrl(CategoryInterface $category)
    {
        return $this->url->getCategoryUrl($category);
    }

    /**
     * Get additional css class for category item
     *
     * @param int $categoryItemIndex
     * @return string
     */
    public function getAdditionalClass($categoryItemIndex)
    {
        $cssClasses = '';
        $categoryLimit = $this->getNumCategoriesToDisplay();
        if ($categoryLimit > 0 && $categoryItemIndex > $categoryLimit) {
            $cssClasses .= self::HIDE_CSS_CLASS;
        }
        if ($categoryLimit > 0 && $categoryItemIndex == $categoryLimit) {
            $cssClasses .= self::SHADED_CSS_CLASS;
        }
        if ($this->getCurrentCategory()) {
            $cssClasses .= self::SUBCATEGORY_CSS_CLASS;
        }
        return $cssClasses;
    }

    /**
     * Check if need to display show more links
     *
     * @param int $categoryItemIndex
     * @return bool
     */
    public function isNeedToDisplayShowMoreLinks($categoryItemIndex)
    {
        $categoryLimit = $this->getNumCategoriesToDisplay();
        if ($categoryLimit > 0 && $categoryItemIndex > $categoryLimit) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        return [\Aheadworks\Blog\Model\Category::CACHE_TAG_CATEGORY_SIDEBAR];
    }

    /**
     * Retrieve blog home url
     *
     * @return string
     */
    public function getBlogHomeUrl()
    {
        return $this->url->getBlogHomeUrl();
    }
}
