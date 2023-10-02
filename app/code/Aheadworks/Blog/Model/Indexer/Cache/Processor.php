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
namespace Aheadworks\Blog\Model\Indexer\Cache;

use Aheadworks\Blog\Model\Post;
use Magento\Framework\Indexer\CacheContext as IndexerCacheContext;
use Magento\Framework\Indexer\CacheContextFactory as IndexerCacheContextFactory;
use Aheadworks\Blog\Model\Indexer\Cache\Processor\Cleaner as IndexerCacheCleaner;
use Magento\Catalog\Model\Product as ProductModel;

/**
 * Class Processor
 */
class Processor
{
    /**
     * @var IndexerCacheContextFactory
     */
    private $indexerCacheContextFactory;

    /**
     * @var IndexerCacheCleaner
     */
    private $indexerCacheCleaner;

    /**
     * @param IndexerCacheContextFactory $indexerCacheContextFactory
     * @param IndexerCacheCleaner $indexerCacheCleaner
     */
    public function __construct(
        IndexerCacheContextFactory $indexerCacheContextFactory,
        IndexerCacheCleaner $indexerCacheCleaner
    ) {
        $this->indexerCacheContextFactory = $indexerCacheContextFactory;
        $this->indexerCacheCleaner = $indexerCacheCleaner;
    }

    /**
     * Update the cache after full reindex
     *
     * @return $this
     */
    public function processFullReindex()
    {
        /** @var IndexerCacheContext $indexerCacheContext */
        $indexerCacheContext = $this->indexerCacheContextFactory->create();
        $indexerCacheContext->registerTags(
            [
                ProductModel::CACHE_TAG,
                Post::CACHE_TAG
            ]
        );
        $this->indexerCacheCleaner->execute(
            $indexerCacheContext
        );
        return $this;
    }
}