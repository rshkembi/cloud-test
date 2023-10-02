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

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Author
 * @package Aheadworks\Blog\Model\Sitemap\ItemsProvider
 */
class Author extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function getItems($storeId)
    {
        $authorItems = [];
        foreach ($this->getAuthors() as $author) {
            $authorItems[$author->getId()] = new DataObject(
                [
                    'id' => $author->getId(),
                    'url' => $this->url->getAuthorRoute($author, $storeId),
                    'updated_at' => $this->getCurrentDateTime()
                ]
            );
        }

        return [new DataObject(
            [
                'changefreq' => $this->getChangeFreq($storeId),
                'priority' => $this->getPriority($storeId),
                'collection' => $authorItems
            ]
        )];
    }

    /**
     * {@inheritdoc}
     */
    public function getItems23x($storeId)
    {
        $authorItems = [];
        foreach ($this->getAuthors() as $author) {
            $authorItems[] = $this->getSitemapItem(
                [
                    'url' => $this->url->getAuthorRoute($author, $storeId),
                    'priority' => $this->getPriority($storeId),
                    'changeFrequency' => $this->getChangeFreq($storeId),
                    'updatedAt' => $this->getCurrentDateTime()
                ]
            );
        }

        return $authorItems;
    }

    /**
     * Retrieves list of authors
     *
     * @return AuthorInterface[]
     */
    private function getAuthors()
    {
        return $this->authorRepository->getList($this->searchCriteriaBuilder->create())->getItems();
    }
}
