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

use Aheadworks\Blog\Model\UrlRewrites\Service\Config as UrlRewriteConfigService;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class GenerateControllerRewrites implements DataPatchInterface
{
    /**
     * GenerateControllerRewrites constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param UrlRewriteConfigService $urlRewriteConfigService
     */
    public function __construct(
        private readonly ModuleDataSetupInterface $moduleDataSetup,
        private readonly UrlRewriteConfigService $urlRewriteConfigService
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
     * Generates blog controller rewrites(blog uses magento url rewrites to process urls from ver. 2.8.1)
     *
     * @return $this
     * @throws LocalizedException
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $this->urlRewriteConfigService->generateAllRewrites();
        $this->moduleDataSetup->getConnection()->endSetup();

        return $this;
    }
}
