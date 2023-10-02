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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogGraphQl\Model\ThirdPartyModule\PageBuilder;

use Magento\Framework\ObjectManagerInterface;
use Aheadworks\BlogGraphQl\Model\ThirdPartyModule\Manager as ModuleManager;
use Magento\PageBuilder\Model\Filter\Template as TemplateFilter;

/**
 * Class PageBuilderTemplateFilterFactory
 * @package Aheadworks\BlogGraphQl\Model\ThirdPartyModule\PageBuilder
 */
class PageBuilderTemplateFilterFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param ModuleManager $moduleManager
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ModuleManager $moduleManager
    ) {
        $this->objectManager = $objectManager;
        $this->moduleManager = $moduleManager;
    }

    /**
     * Create template filter if possible
     *
     * @return TemplateFilter|null
     */
    public function create()
    {
        return $this->moduleManager->isMagePageBuilderModuleEnabled()
            ? $this->objectManager->create(TemplateFilter::class)
            : null;
    }
}
