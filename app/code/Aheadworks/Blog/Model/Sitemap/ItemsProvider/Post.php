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

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Model\Source\Post\Status;

/**
 * Class Post
 * @package Aheadworks\Blog\Model\Sitemap\ItemsProvider
 */
class Post extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {
        $postItems = [];
        foreach ($this->getPosts($storeId) as $post) {
            $postItems[$post->getId()] = new DataObject(
                [
                    'id' => $post->getId(),
                    'url' => $this->url->getPostRoute($post, $storeId),
                    'updated_at' => $this->getCurrentDateTime()
                ]
            );
        }

        return [new DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => $postItems
            ]
        )];
    }

    /**
     * {@inheritdoc}
     */
    public function getItems23x($storeId)
    {
        $postItems = [];
        foreach ($this->getPosts($storeId) as $post) {
            $postItems[] = $this->getSitemapItem(
                [
                    'url' => $this->url->getPostRoute($post, $storeId),
                    'priority' => $this->getPriority($storeId),
                    'changeFrequency' => $this->getChangeFreq($storeId),
                    'updatedAt' => $this->getCurrentDateTime()
                ]
            );
        }

        return $postItems;
    }

    /**
     * Retrieves list of posts
     *
     * @param int $storeId
     * @return PostInterface[]
     * @throws LocalizedException
     */
    private function getPosts($storeId)
    {
        $this->searchCriteriaBuilder
            ->addFilter('publish_date', $this->getCurrentDateTime(), 'lteq')
            ->addFilter('status', Status::PUBLICATION)
            ->addFilter(PostInterface::STORE_IDS, $storeId);

        return $this->postRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
