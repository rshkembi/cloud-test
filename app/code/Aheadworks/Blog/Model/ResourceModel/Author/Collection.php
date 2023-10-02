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
namespace Aheadworks\Blog\Model\ResourceModel\Author;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Author;
use Aheadworks\Blog\Model\ResourceModel\Author as ResourceAuthor;
use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;
use Aheadworks\Blog\Model\ResourceModel\AbstractCollection;

/**
 * Class Collection
 * @package Aheadworks\Blog\Model\ResourceModel\Author
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = AuthorInterface::ID;

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Author::class, ResourceAuthor::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->joinPostCount();

        return $this;
    }

    /**
     * Join post count
     *
     * @return $this
     */
    private function joinPostCount()
    {
        $postCountSelect = $this->getConnection()->select()
            ->from(
                ['tmp' => $this->getTable(ResourcePost::BLOG_POST_TABLE)],
                [
                    AuthorInterface::POSTS_COUNT => new \Zend_Db_Expr('COUNT(*)'),
                    PostInterface::AUTHOR_ID
                ]
            )->group('tmp.' . PostInterface::AUTHOR_ID);

        $this->getSelect()
            ->joinLeft(
                ['posts' => $postCountSelect],
                'posts.' . PostInterface::AUTHOR_ID . ' = main_table.' . AuthorInterface::ID,
                [AuthorInterface::POSTS_COUNT => new \Zend_Db_Expr('COALESCE(posts.posts_count, 0)')]
            );

        return $this;
    }

    /**
     * Dummy method, while authors have no store binding
     *
     * @param int|array $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        return $this;
    }
}
