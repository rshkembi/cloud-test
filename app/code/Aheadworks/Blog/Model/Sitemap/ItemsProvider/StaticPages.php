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

use Magento\Framework\DataObject;

/**
 * Class StaticPages
 * @package Aheadworks\Blog\Model\Sitemap\ItemsProvider
 */
class StaticPages extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {
        return [$this->getBlogItem($storeId), $this->getAuthorsItem($storeId)];
    }

    /**
     * {@inheritdoc}
     */
    public function getItems23x($storeId)
    {
        return [$this->getBlogItem23x($storeId), $this->getAuthorsItem23x($storeId)];
    }

    /**
     * Retrieves blog home page sitemap item
     *
     * @param int $storeId
     * @return DataObject
     */
    private function getBlogItem($storeId)
    {
        return new DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => [
                    new DataObject(
                        [
                            'id' => 'blog_home',
                            'url' => $this->config->getRouteToBlog($storeId),
                            'updated_at' => $this->getCurrentDateTime()
                        ]
                    )
                ]
            ]
        );
    }

    /**
     * Retrieves blog home page sitemap item 2.3.x
     *
     * @param int $storeId
     * @return DataObject|\Magento\Sitemap\Model\SitemapItemInterface
     */
    private function getBlogItem23x($storeId)
    {
        return $this->getSitemapItem(
            [
                'url' => $this->config->getRouteToBlog($storeId),
                'priority' => $this->getPriority($storeId),
                'changeFrequency' => $this->getChangeFreq($storeId),
                'updatedAt' => $this->getCurrentDateTime()
            ]
        );
    }

    /**
     * Retrieves authors page sitemap item
     *
     * @param int $storeId
     * @return DataObject
     */
    private function getAuthorsItem($storeId)
    {
        return new DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => [
                    new DataObject(
                        [
                            'id' => 'authors',
                            'url' => $this->url->getAuthorsPageRoute($storeId),
                            'updated_at' => $this->getCurrentDateTime()
                        ]
                    )
                ]
            ]
        );
    }

    /**
     * Retrieves authors page sitemap item 2.3.x
     *
     * @param int $storeId
     * @return DataObject|\Magento\Sitemap\Model\SitemapItemInterface
     */
    private function getAuthorsItem23x($storeId)
    {
        return $this->getSitemapItem(
            [
                'url' => $this->url->getAuthorsPageRoute($storeId),
                'priority' => $this->getPriority($storeId),
                'changeFrequency' => $this->getChangeFreq($storeId),
                'updatedAt' => $this->getCurrentDateTime()
            ]
        );
    }
}
