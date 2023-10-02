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

/**
 * Class SingleThreadProcessor
 *
 * @package Aheadworks\Blog\Model\Indexer\ProductPost\Action\Full
 */
class SingleThreadProcessor
{
    /**
     * @var ResourceProductPostIndexer
     */
    private $resourceProductPostIndexer;

    /**
     * @param ResourceProductPostIndexer $resourceProductPostIndexer
     */
    public function __construct(
        ResourceProductPostIndexer $resourceProductPostIndexer
    ) {
        $this->resourceProductPostIndexer = $resourceProductPostIndexer;
    }

    /**
     * Execute Full reindex in single thread mode (old way)
     *
     * @param array|int|null $ids
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute($ids = null)
    {
        try {
            $this->resourceProductPostIndexer->reindexAll();
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\LocalizedException(__($e->getMessage()), $e);
        }
    }
}
