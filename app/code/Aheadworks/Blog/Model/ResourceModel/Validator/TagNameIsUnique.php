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
namespace Aheadworks\Blog\Model\ResourceModel\Validator;

use Aheadworks\Blog\Api\Data\TagInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\EntityManager\MetadataPool;

/**
 * Class TagNameIsUnique
 * @package Aheadworks\Blog\Model\ResourceModel\Validator
 */
class TagNameIsUnique
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param MetadataPool $metadataPool
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        MetadataPool $metadataPool,
        ResourceConnection $resourceConnection
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Checks that tag name is unique
     *
     * @param TagInterface $entity
     * @return bool
     */
    public function validate($entity)
    {
        $metadata = $this->metadataPool->getMetadata(TagInterface::class);
        $connection = $this->resourceConnection
            ->getConnectionByName($metadata->getEntityConnectionName());

        $select = $connection->select()
            ->from($metadata->getEntityTable())
            ->where('name = :name');
        $bind = ['name' => $entity->getName()];
        if ($entity->getId()) {
            $select->where($metadata->getIdentifierField() . ' <> :id');
            $bind['id'] = $entity->getId();
        }
        if (!$connection->fetchRow($select, $bind)) {
            return true;
        }
        return false;
    }
}
