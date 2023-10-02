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
namespace Aheadworks\Blog\Block\Adminhtml\Widget\Instance\Grid;

/**
 * Class Posts
 */
class Posts extends AbstractGrid
{
    /**
     * @var string
     */
    protected $typeCode = 'blog_posts';

    /**
     * @var string
     */
    protected $indexColumn = 'in_posts';

    /**
     * @var string
     */
    protected $identifier = 'id';

    /**
     * Prepare columns for posts grid
     *
     * @return AbstractGrid
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            $this->indexColumn,
            [
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => $this->indexColumn,
                'inline_css' => 'checkbox entities',
                'field_name' => $this->indexColumn,
                'values' => $this->getSelectedNodes(),
                'align' => 'center',
                'index' => 'id',
                'use_index' => true
            ]
        );

        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'sortable' => true,
                'index' => 'id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id'
            ]
        );

        $this->addColumn(
            'post_title',
            [
                'header' => __('Title'),
                'sortable' => true,
                'index' => 'title',
                'header_css_class' => 'col-title',
                'column_css_class' => 'col-title'
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * @inheritDoc
     */
    protected function getTypeCode()
    {
        return $this->typeCode;
    }

    /**
     * @inheritDoc
     */
    protected function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @inheritDoc
     */
    protected function getIndexColumn()
    {
        return $this->indexColumn;
    }
}