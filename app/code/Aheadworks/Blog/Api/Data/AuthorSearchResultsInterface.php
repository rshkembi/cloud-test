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
namespace Aheadworks\Blog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface AuthorSearchResultsInterface
 * @package Aheadworks\Blog\Api\Data
 */
interface AuthorSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get authors list
     *
     * @return \Aheadworks\Blog\Api\Data\AuthorInterface[]
     */
    public function getItems();

    /**
     * Set authors list
     *
     * @param \Aheadworks\Blog\Api\Data\AuthorInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
