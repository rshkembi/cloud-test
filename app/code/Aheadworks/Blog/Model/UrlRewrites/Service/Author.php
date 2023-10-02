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
namespace Aheadworks\Blog\Model\UrlRewrites\Service;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Model\UrlRewrites\RewriteUpdater;
use Magento\Framework\Exception\LocalizedException;
use Magento\UrlRewrite\Model\MergeDataProvider;
use Magento\UrlRewrite\Model\MergeDataProviderFactory;
use Aheadworks\Blog\Model\StoreResolver;
use Aheadworks\Blog\Model\UrlRewrites\Generator\Entity\Author\Author as RewriteGenerator;

/**
 * Class Author
 * @package Aheadworks\Blog\Model\UrlRewrites\Service
 */
class Author
{
    /**
     * @var RewriteUpdater
     */
    private $rewriteUpdater;

    /**
     * @var MergeDataProviderFactory
     */
    private $mergeDataProviderFactory;

    /**
     * @var StoreResolver
     */
    private $storeResolver;

    /**
     * @var RewriteGenerator
     */
    private $rewriteGenerator;

    /**
     * Author constructor.
     * @param RewriteUpdater $rewriteUpdater
     * @param MergeDataProviderFactory $mergeDataProviderFactory
     * @param StoreResolver $storeResolver
     * @param RewriteGenerator $rewriteGenerator
     */
    public function __construct(
        RewriteUpdater $rewriteUpdater,
        MergeDataProviderFactory $mergeDataProviderFactory,
        StoreResolver $storeResolver,
        RewriteGenerator $rewriteGenerator
    ) {
        $this->rewriteUpdater = $rewriteUpdater;
        $this->mergeDataProviderFactory = $mergeDataProviderFactory;
        $this->storeResolver = $storeResolver;
        $this->rewriteGenerator = $rewriteGenerator;
    }

    /**
     * Updates permanent redirects
     *
     * @param AuthorInterface $author
     * @param AuthorInterface $origAuthor
     * @throws LocalizedException
     */
    public function updateRewrites($author, $origAuthor)
    {
        $allStores = $this->storeResolver->getAllStoreIds();

        /** @var MergeDataProvider $mergeDataProvider */
        $mergeDataProvider = $this->mergeDataProviderFactory->create();

        foreach ($allStores as $store) {
            $storeRewrites = $this->rewriteGenerator->generate($store, $author, $origAuthor);
            $mergeDataProvider->merge($storeRewrites);
        }

        $this->rewriteUpdater->update($mergeDataProvider->getData());
    }
}
