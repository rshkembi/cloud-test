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
namespace Aheadworks\Blog\Model\Sitemap\ItemsProvider;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Source\Category\Status;

/**
 * Class Category
 * @package Aheadworks\Blog\Model\Sitemap\ItemsProvider
 */
class Category extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {
        $categoryItems = [];
        foreach ($this->getCategories($storeId) as $category) {
            $categoryItems[$category->getId()] = new DataObject(
                [
                    'id' => $category->getId(),
                    'url' => $this->url->getCategoryRoute($category, $storeId),
                    'updated_at' => $this->getCurrentDateTime()
                ]
            );
        }

        return [new DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => $categoryItems
            ]
        )];
    }

    /**
     * {@inheritdoc}
     */
    public function getItems23x($storeId)
    {
        $categoryItems = [];
        foreach ($this->getCategories($storeId) as $category) {
            $categoryItems[] = $this->getSitemapItem(
                [
                    'url' => $this->url->getCategoryRoute($category, $storeId),
                    'priority' => $this->getPriority($storeId),
                    'changeFrequency' => $this->getChangeFreq($storeId),
                    'updatedAt' => $this->getCurrentDateTime()
                ]
            );
        }

        return $categoryItems;
    }

    /**
     * Retrieves list of categories
     *
     * @param int $storeId
     * @return CategoryInterface[]
     * @throws LocalizedException
     */
    private function getCategories($storeId)
    {
        $this->searchCriteriaBuilder
            ->addFilter('status', Status::ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $storeId);

        return $this->categoryRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
