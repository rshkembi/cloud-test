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
namespace Aheadworks\Blog\Model\Indexer\ProductPost\Action\Full;

use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost as ResourceProductPostIndexer;
use Aheadworks\Blog\Model\Indexer\MultiThread\PostDimensionProvider;
use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Blog\Model\Indexer\MultiThread\PostDimension;
use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost;

/**
 * Class MultiThreadProcessor
 *
 * @package Aheadworks\Blog\Model\Indexer\ProductPost\Action\Full
 */
class MultiThreadProcessor
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var PostDimensionProvider
     */
    private $dimensionProvider;

    /**
     * @var ResourceProductPostIndexer
     */
    private $resourceProductPostIndexer;

    /**
     * @param ProductPost $resourceProductPostIndexer
     * @param PostDimensionProvider $dimensionProvider
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(
        ResourceProductPostIndexer $resourceProductPostIndexer,
        PostDimensionProvider $dimensionProvider,
        ObjectManagerInterface $objectManager
    ) {
        $this->resourceProductPostIndexer = $resourceProductPostIndexer;
        $this->dimensionProvider = $dimensionProvider;
        $this->objectManager = $objectManager;
    }

    /**
     * Execute Full reindex in multi thread mode since M2.2.6
     *
     * @param array|int|null $ids
     * @return void
     * @throws \Exception
     */
    public function execute($ids = null)
    {
        $processManager = $this->objectManager->create(\Magento\Indexer\Model\ProcessManager::class);
        $userFunctions = [];
        foreach ($this->dimensionProvider->getIterator() as $dimension) {
            $userFunctions[] = function () use ($dimension) {
                $this->reindexByDimension($dimension);
            };
        }
        $processManager->execute($userFunctions);

        $this->resourceProductPostIndexer->swapIndexTable();
    }

    /**
     * Reindex by dimension
     *
     * @param PostDimension $dimension
     * @throws \Exception
     */
    private function reindexByDimension($dimension)
    {
        $indexTable = $this->resourceProductPostIndexer->getWorkingTableName();
        $this->resourceProductPostIndexer->reindexDimension($dimension, $indexTable);
    }
}
