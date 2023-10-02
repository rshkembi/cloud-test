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

namespace Aheadworks\Blog\Setup\Patch\Schema;

use Magento\Cms\Api\Data\BlockInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class AddBlockForeignKey implements SchemaPatchInterface, PatchVersionInterface
{
    /**
     * @param SchemaSetupInterface $schemaSetup
     * @param MetadataPool $metadataPool
     */
    public function __construct(
        private readonly SchemaSetupInterface $schemaSetup,
        private readonly MetadataPool $metadataPool
    ) {
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Apply schema patch
     *
     * @return $this
     * @throws \Exception
     */
    public function apply()
    {
        $connection = $this->schemaSetup->getConnection();
        $connection->addForeignKey(
            $this->schemaSetup->getFkName(
                'aw_blog_category',
                'cms_block_id',
                'cms_block',
                'block_id'
            ),
            $this->schemaSetup->getTable('aw_blog_category'),
            'cms_block_id',
            $this->schemaSetup->getTable('cms_block'),
            $this->metadataPool->getMetadata(BlockInterface::class)
                ->getLinkField(),
            Table::ACTION_SET_NULL
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getVersion()
    {
        return '2.1.1';
    }
}
