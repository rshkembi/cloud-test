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

use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\Api\Data\TagCloudItemInterface;
use Magento\Store\Model\Store;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;
use Aheadworks\Blog\Model\ResourceModel\Tag as ResourceTag;

/**
 * Class TagCloudItem
 * @package Aheadworks\Blog\Model\ResourceModel
 */
class TagCloudItem extends AbstractDb
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param Context $context
     * @param MetadataPool $metadataPool
     * @param null $connectionName
     */
    public function __construct(
        Context $context,
        MetadataPool $metadataPool,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init(ResourceTag::BLOG_TAG_TABLE, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return $this->_resources->getConnectionByName(
            $this->metadataPool->getMetadata(TagInterface::class)->getEntityConnectionName()
        );
    }

    /**
     * Retrieves number of posts corresponding to given tag ID for particular store view
     *
     * @param int $tagId
     * @param int $storeId
     * @param string[] $postStatus
     * @return int
     */
    public function getPostCount($tagId, $storeId, $postStatus = [])
    {
        $select = $this->getConnection()->select()
            ->from(['post_tag_table' => $this->_resources->getTableName(ResourcePost::BLOG_POST_TAG_TABLE)], [])
            ->joinLeft(
                ['post_store_table' => $this->_resources->getTableName(ResourcePost::BLOG_POST_STORE_TABLE)],
                'post_tag_table.post_id = post_store_table.post_id',
                []
            )
            ->where('post_tag_table.tag_id = ?', $tagId)
            ->where('post_store_table.store_id IN (?)', [$storeId, Store::DEFAULT_STORE_ID])
            ->columns(
                [TagCloudItemInterface::POST_COUNT => new \Zend_Db_Expr('COUNT(post_tag_table.post_id)')]
            );

        if (!empty($postStatus)) {
            $select
                ->joinLeft(
                    ['post_table' => $this->_resources->getTableName(ResourcePost::BLOG_POST_TABLE)],
                    'post_tag_table.post_id = post_table.id',
                    []
                )->where('post_table.status IN (?)', $postStatus);
        }

        return (int)$this->getConnection()->fetchOne($select);
    }
}
