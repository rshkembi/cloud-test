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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogSearch\Model\Indexer\Post;

use Magento\Framework\Indexer\SaveHandler\IndexerInterface;
use Magento\Store\Model\StoreDimensionProvider;
use Aheadworks\BlogSearch\Model\Indexer\Post\Fulltext\Action\Full as FullAction;
use Aheadworks\BlogSearch\Model\Indexer\Handler\Factory as IndexerHandlerFactory;

/**
 * Class Fulltext
 */
class Fulltext implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * Indexer ID in configuration
     */
    public const INDEXER_ID = 'aheadworks_blogsearch_post_fulltext';

    /**
     * @var IndexerInterface
     */
    private $indexerHandler;

    /**
     * @param StoreDimensionProvider $storeDimensionProvider
     * @param IndexerHandlerFactory $indexerHandlerFactory
     * @param FullAction $fullAction
     */
    public function __construct(
        private readonly StoreDimensionProvider $storeDimensionProvider,
        IndexerHandlerFactory $indexerHandlerFactory,
        private readonly FullAction $fullAction
    ) {
        $this->indexerHandler = $indexerHandlerFactory->create();
    }

    /**
     * @inheritdoc
     */
    public function execute($postIds)
    {
        foreach ($this->storeDimensionProvider->getIterator() as $storeDimension) {
            $storeId = current($storeDimension)->getValue();

            if ($this->indexerHandler->isAvailable($storeDimension)) {
                $this->indexerHandler->deleteIndex($storeDimension, new \ArrayIterator($postIds));
                $this->indexerHandler->saveIndex($storeDimension, $this->fullAction->rebuildStoreIndex($storeId, $postIds));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function executeFull()
    {
        foreach ($this->storeDimensionProvider->getIterator() as $storeDimension) {
            $storeId = current($storeDimension)->getValue();

            $this->indexerHandler->cleanIndex($storeDimension);
            $this->indexerHandler->saveIndex($storeDimension, $this->fullAction->rebuildStoreIndex($storeId));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function executeList(array $postIds)
    {
        $this->execute($postIds);
    }

    /**
     * {@inheritDoc}
     */
    public function executeRow($postId)
    {
        $this->execute([$postId]);
    }
}
