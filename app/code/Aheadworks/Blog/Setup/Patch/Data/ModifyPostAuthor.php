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

namespace Aheadworks\Blog\Setup\Patch\Data;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\Author\Resolver;
use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class ModifyPostAuthor implements DataPatchInterface, PatchVersionInterface
{
    /**
     * ModifyPostAuthor constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Resolver $authorResolver
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly Resolver $authorResolver
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
     * Modify post author
     *
     * @return $this
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $select = $connection->select()->from($this->moduleDataSetup->getTable(ResourcePost::BLOG_POST_TABLE));
        $posts = $connection->fetchAll($select);

        foreach ($posts as $post) {
            $authorId = $this->authorResolver->resolveId($post, 'author_name');
            $connection->update(
                $this->moduleDataSetup->getTable(ResourcePost::BLOG_POST_TABLE),
                [PostInterface::AUTHOR_ID => $authorId],
                PostInterface::ID . ' = ' . $post[PostInterface::ID]
            );
        }
        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getVersion()
    {
        return '2.6.0';
    }
}
