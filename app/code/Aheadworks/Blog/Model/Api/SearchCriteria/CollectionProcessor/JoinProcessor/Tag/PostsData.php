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

namespace Aheadworks\Blog\Model\Api\SearchCriteria\CollectionProcessor\JoinProcessor\Tag;

use Magento\Framework\Api\SearchCriteria\CollectionProcessor\JoinProcessor\CustomJoinInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DB\Select;

class PostsData implements CustomJoinInterface
{
    /** Alias of table, that will be joined */
    const BLOG_POST_TAG_ALIAS = "bpt";
    const BLOG_POST_STORE_ALIAS = "bps";
    const BLOG_POST_ALIAS = "bp";

    /**
     * @param \Aheadworks\Blog\Model\ResourceModel\Tag\Collection $collection
     * @return bool
     * @throws \Zend_Db_Select_Exception
     */
    public function apply(AbstractDb $collection)
    {
        $success = false;

        $isNotApplied = !array_key_exists(
            self::BLOG_POST_TAG_ALIAS,
            $collection->getSelect()->getPart(Select::FROM)
        );

        if ($isNotApplied) {
            $collection->joinPostTables(
                self::BLOG_POST_TAG_ALIAS,
                self::BLOG_POST_STORE_ALIAS,
                self::BLOG_POST_ALIAS
            );
            $success = true;
        }

        return $success;
    }
}
