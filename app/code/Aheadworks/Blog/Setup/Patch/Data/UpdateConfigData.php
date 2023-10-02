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

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Api\WebsiteRepositoryInterface;

class UpdateConfigData implements DataPatchInterface
{
    private array $paths = [
        'aw_blog/general/comments_enabled' => 'aw_blog/comments/comments_enabled',
        'aw_blog/general/disqus_forum_code' => 'aw_blog/comments/disqus_forum_code',
        'aw_blog/general/disqus_secret_key' => 'aw_blog/comments/disqus_secret_key',
        'aw_blog/general/facebook_app_id' => 'aw_blog/comments/facebook_app_id',
        'aw_blog/general/twitter_site' => 'aw_blog/comments/twitter_site'
    ];

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param WebsiteRepositoryInterface $websiteRepository
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly WebsiteRepositoryInterface $websiteRepository
    ) {
    }

    /**
     * Get array of patches that have to be executed prior to this.
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Update config data
     *
     * @return $this
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $connection = $this->moduleDataSetup->getConnection();
        $websites = $this->websiteRepository->getList();
        foreach ($websites as $website) {
            foreach ($this->paths as $oldPath => $newPath) {
                $connection->update(
                    $this->moduleDataSetup->getTable('core_config_data'),
                    ['path' => $newPath],
                    ['scope_id = ?' => $website->getId(), 'path = ?' => $oldPath]
                );
            }
        }

        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }
}
