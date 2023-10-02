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
declare(strict_types=1);

namespace Aheadworks\Blog\Api\Data\Email\Subscriber;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface DataRowSearchResultsInterface
 */
interface DataRowSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get email subscriber data row list
     *
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface[]
     */
    public function getItems();

    /**
     * Set email subscriber data row list
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
