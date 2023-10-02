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
namespace Aheadworks\Blog\Model\ResourceModel\Post\Relation\Category;

use Magento\Framework\App\ResourceConnection;
use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;

/**
 * Class SaveHandler
 * @package Aheadworks\Blog\Model\ResourceModel\Post\Relation\Category
 */
class SaveHandler implements ExtensionInterface
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(MetadataPool $metadataPool, ResourceConnection $resourceConnection)
    {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        $entityId = (int)$entity->getId();
        $categoryIds = $entity->getCategoryIds() ? : [];
        $categoryIdsOrig = $this->getCategoryIds($entityId);

        $toInsert = array_diff($categoryIds, $categoryIdsOrig);
        $toDelete = array_diff($categoryIdsOrig, $categoryIds);

        $connection = $this->getConnection();
        $tableName = $this->resourceConnection->getTableName(ResourcePost::BLOG_POST_CATEGORY_TABLE);

        if ($toInsert) {
            $data = [];
            foreach ($toInsert as $categoryId) {
                $data[] = [
                    'post_id' => (int)$entityId,
                    'category_id' => (int)$categoryId,
                ];
            }
            $connection->insertMultiple($tableName, $data);
        }
        if (count($toDelete)) {
            $connection->delete(
                $tableName,
                ['post_id = ?' => $entityId, 'category_id IN (?)' => $toDelete]
            );
        }
        return $entity;
    }

    /**
     * Get category IDs to which entity is assigned
     *
     * @param int $entityId
     * @return array
     */
    private function getCategoryIds($entityId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->resourceConnection->getTableName(ResourcePost::BLOG_POST_CATEGORY_TABLE), 'category_id')
            ->where('post_id = :id');
        return $connection->fetchCol($select, ['id' => $entityId]);
    }

    /**
     * Get connection
     *
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     * @throws \Exception
     */
    private function getConnection()
    {
        return $this->resourceConnection->getConnectionByName(
            $this->metadataPool->getMetadata(PostInterface::class)->getEntityConnectionName()
        );
    }
}
