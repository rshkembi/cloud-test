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
namespace Aheadworks\Blog\Model\ResourceModel\Category;

use Aheadworks\Blog\Model\Category;
use Aheadworks\Blog\Model\ResourceModel\Category as ResourceCategory;

/**
 * Class Collection
 * @package Aheadworks\Blog\Model\ResourceModel\Category
 */
class Collection extends \Aheadworks\Blog\Model\ResourceModel\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(Category::class, ResourceCategory::class);
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('id', 'name');
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterLoad()
    {
        $this->attachStores(ResourceCategory::BLOG_CATEGORY_STORE_TABLE, 'id', 'category_id');
        return parent::_afterLoad();
    }

    /**
     * {@inheritdoc}
     */
    protected function _renderFiltersBefore()
    {
        $this->joinStoreLinkageTable(ResourceCategory::BLOG_CATEGORY_STORE_TABLE, 'id', 'category_id');
        parent::_renderFiltersBefore();
    }
}
