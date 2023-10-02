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
namespace Aheadworks\Blog\Model\Source\UrlRewrite;

/**
 * Class EntityType
 * @package Aheadworks\Blog\Model\Source\UrlRewrite
 */
class EntityType
{
    /**#@+
     * Entity types for url rewrites
     */
    const TYPE_POST = 'aw-blog-post';
    const TYPE_AUTHOR = 'aw-blog-author';
    const TYPE_CATEGORY = 'aw-blog-category';
    const TYPE_BLOG_HOME_PAGE = 'aw-blog-home-page';
    const TYPE_AUTHOR_LIST_PAGE = 'aw-blog-author-list-page';
    const TYPE_TAG = 'aw-blog-tag';
    const TYPE_SEARCH = 'aw-blog-search';
    /**#@-*/

    /**
     * @return array
     */
    public static function getEntityArray()
    {
        return [
            self::TYPE_POST,
            self::TYPE_AUTHOR,
            self::TYPE_CATEGORY
        ];
    }
}
