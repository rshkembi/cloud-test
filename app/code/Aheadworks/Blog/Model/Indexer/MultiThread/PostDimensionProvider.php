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
namespace Aheadworks\Blog\Model\Indexer\MultiThread;

use Aheadworks\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

/**
 * Class PostDimensionProvider
 *
 * @package Aheadworks\Blog\Model\Indexer\MultiThread
 */
class PostDimensionProvider implements DimensionProviderInterface
{
    /**
     * @var PostCollectionFactory
     */
    private $collectionFactory;

    /**
     * @var PostDimensionFactory
     */
    private $dimensionFactory;

    /**
     * @var int
     */
    private $dimensionSize;

    /**
     * @param PostCollectionFactory $collectionFactory
     * @param PostDimensionFactory $dimensionFactory
     * @param int $dimensionSize
     */
    public function __construct(
        PostCollectionFactory $collectionFactory,
        PostDimensionFactory $dimensionFactory,
        $dimensionSize = 200
    ) {
        $this->dimensionFactory = $dimensionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->dimensionSize = $dimensionSize;
    }

    /**
     * Get iterator for post dimensions
     *
     * @return PostDimension[]|\Traversable
     */
    public function getIterator()
    {
        $offset = 0;

        do {
            $collection = $this->collectionFactory->create();
            $collection->getSelect()->limit($this->dimensionSize, $offset);
            $offset += $this->dimensionSize;

            $loadedIds = $collection->getCurrentLoadedIds();
            if (is_array($loadedIds) && count($loadedIds) > 0) {
                yield $this->dimensionFactory->create(PostDimension::DIMENSION_NAME . $loadedIds[0], $loadedIds);
            }
        } while ($loadedIds);
    }
}
