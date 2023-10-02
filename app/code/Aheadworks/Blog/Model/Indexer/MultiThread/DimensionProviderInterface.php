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

/**
 * Interface DimensionProviderInterface
 *
 * It is created for compatibility with 2.1.X Magento
 *
 * @package Aheadworks\Blog\Model\Indexer\MultiThread
 */
interface DimensionProviderInterface extends \IteratorAggregate
{
    /**
     * Get Dimension Iterator.
     *
     * @return \Traversable
     */
    #[\ReturnTypeWillChange]
    public function getIterator();
}
