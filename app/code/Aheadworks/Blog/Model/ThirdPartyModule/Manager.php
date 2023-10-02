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
namespace Aheadworks\Blog\Model\ThirdPartyModule;

use Magento\Framework\Module\ModuleListInterface;

/**
 * Class Manager
 * @package Aheadworks\Blog\Model\ThirdPartyModule
 */
class Manager
{
    /**
     * Magento Page Builder module name
     */
    const MAGE_PAGE_BUILDER_MODULE_NAME = 'Magento_PageBuilder';

    /**
     * @var ModuleListInterface
     */
    private $moduleList;

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        ModuleListInterface $moduleList
    ) {
        $this->moduleList = $moduleList;
    }

    /**
     * Check if Magento Page Builder module enabled
     *
     * @return bool
     */
    public function isMagePageBuilderModuleEnabled()
    {
        return $this->moduleList->has(self::MAGE_PAGE_BUILDER_MODULE_NAME);
    }
}
