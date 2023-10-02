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

use Aheadworks\Blog\Model\ResourceModel\Post as ResourcePost;
use Aheadworks\Blog\Model\Source\Post\Status;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;

class UpdatePostStatus implements DataPatchInterface, PatchVersionInterface
{
    /**
     * UpdatePostStatus constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup
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
     * Update post status
     *
     * @return $this
     * @throws \Exception
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();

        $now = new \DateTime('now', new \DateTimeZone('UTC'));
        $select = $connection->select()
            ->from($this->moduleDataSetup->getTable(ResourcePost::BLOG_POST_TABLE), ['id'])
            ->where('publish_date > ?', $now);
        $postIds = $connection->fetchCol($select);

        if (count($postIds)) {
            $connection->update(
                $this->moduleDataSetup->getTable(ResourcePost::BLOG_POST_TABLE),
                ['status' => Status::SCHEDULED],
                'id IN(' . implode(',', array_values($postIds)) . ')'
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
        return '2.1.0';
    }
}
