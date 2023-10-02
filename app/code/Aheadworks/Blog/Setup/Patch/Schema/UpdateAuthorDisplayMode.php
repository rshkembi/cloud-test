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

use Aheadworks\Blog\Model\ResourceModel\Post;
use Aheadworks\Blog\Model\Source\Post\AuthorDisplayMode;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;

class UpdateAuthorDisplayMode implements SchemaPatchInterface
{
    /**
     * UpdateAuthorDisplayMode constructor.
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
     * Set Default Value Author Display Mode
     *
     * @return $this
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->moduleDataSetup->getConnection()->changeColumn(
            $this->moduleDataSetup->getTable(Post::BLOG_POST_TABLE),
            'author_display_mode',
            'author_display_mode',
            [
                'type' => Table::TYPE_SMALLINT,
                'nullable' => false,
                'unsigned' => false,
                'default' => AuthorDisplayMode::USE_DEFAULT_OPTION,
                'comment' => 'Author Display Mode'
            ]
        );
        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }
}
