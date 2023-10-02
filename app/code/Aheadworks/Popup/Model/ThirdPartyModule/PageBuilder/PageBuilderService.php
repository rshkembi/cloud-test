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
 * @package    Popup
 * @version    1.2.9
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\Popup\Model\ThirdPartyModule\PageBuilder;

use Magento\PageBuilder\Model\Config;
use Aheadworks\Popup\Model\ThirdPartyModule\Manager as ModuleManager;
use Magento\Framework\ObjectManagerInterface;

/**
 * Class PageBuilderService
 */
class PageBuilderService
{
    /**
     * PageBuilderService constructor.
     *
     * @param ObjectManagerInterface $objectManager
     * @param ModuleManager $moduleManager
     */
    public function __construct(private ObjectManagerInterface $objectManager, private ModuleManager $moduleManager)
    {
    }

    /**
     * Is page builder enabled
     *
     * @return bool
     */
    public function isBuilderEnabled(): bool
    {
        $config = $this->getConfig();
        return $config && $config->isEnabled();
    }

    /**
     * Get config
     *
     * @return Config|null
     */
    private function getConfig(): ?Config
    {
        $result = null;
        if ($this->moduleManager->isMagePageBuilderModuleEnabled()) {
            $result = $this->objectManager->create(Config::class);
        }
        return $result;
    }
}
