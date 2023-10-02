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
namespace Aheadworks\Blog\Model\ResourceModel\TagCloudItem;

use Aheadworks\Blog\Api\Data\TagCloudItemSearchResultsInterface;

/**
 * SearchResults for tag cloud items
 */
class SearchResults extends \Magento\Framework\Api\SearchResults implements TagCloudItemSearchResultsInterface
{
    const KEY_MAX_POST_COUNT = 'max_post_count';
    const KEY_MIN_POST_COUNT = 'min_post_count';

    /**
     * {@inheritdoc}
     */
    public function getMaxPostCount()
    {
        return $this->_get(self::KEY_MAX_POST_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setMaxPostCount($maxPostCount)
    {
        return $this->setData(self::KEY_MAX_POST_COUNT, $maxPostCount);
    }

    /**
     * {@inheritdoc}
     */
    public function getMinPostCount()
    {
        return $this->_get(self::KEY_MIN_POST_COUNT);
    }

    /**
     * {@inheritdoc}
     */
    public function setMinPostCount($minPostCount)
    {
        return $this->setData(self::KEY_MIN_POST_COUNT, $minPostCount);
    }
}
