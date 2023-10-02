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
namespace Aheadworks\Blog\Model\ResourceModel;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Author
 * @package Aheadworks\Blog\Model\ResourceModel
 */
class Author extends AbstractDb
{
    /**#@+
     * Constants defined for table names
     */
    const BLOG_AUTHOR_TABLE = 'aw_blog_author';
    const BLOG_AUTHOR_POST_TABLE = 'aw_blog_author_post';
    /**#@-*/

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::BLOG_AUTHOR_TABLE, AuthorInterface::ID);
    }
}
