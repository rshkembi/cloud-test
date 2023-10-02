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

namespace Aheadworks\Blog\Model\ResourceModel\Post\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post\Comment as CommentModel;
use Aheadworks\Blog\Model\ResourceModel\AbstractCollection;
use Aheadworks\Blog\Model\ResourceModel\Post\Comment as CommentResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = CommentInterface::ID;

    /**
     * Init resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            CommentModel::class,
            CommentResourceModel::class
        );
    }

    /**
     * Init select
     *
     * @return $this
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->attachParentAuthorName();

        return $this;
    }

    /**
     * Join post title column
     *
     * @return $this
     */
    private function attachParentAuthorName(): self
    {
        $this->getSelect()->columns(
            ['parent_author_name' => '(SELECT author_name FROM '
                . $this->getMainTable() .
                ' WHERE main_table.reply_to_comment_id = ' . $this->getMainTable() . '.id)']
        );

        return $this;
    }

}
