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
namespace Aheadworks\BlogGraphQl\Model\TemplateFilter;

use Aheadworks\BlogGraphQl\Model\ThirdPartyModule\PageBuilder\PageBuilderTemplateFilterFactory;
use Magento\PageBuilder\Model\Filter\Template as TemplateFilter;

/**
 * Class PageBuilder
 * @package Aheadworks\BlogGraphQl\Model\TemplateFilter
 */
class PageBuilder implements FilterInterface
{
    /**
     * @var PageBuilderTemplateFilterFactory
     */
    private $templateFilterFactory;

    /**
     * @param PageBuilderTemplateFilterFactory $templateFilterFactory
     */
    public function __construct(
        PageBuilderTemplateFilterFactory $templateFilterFactory
    ) {
        $this->templateFilterFactory = $templateFilterFactory;
    }

    /**
     * @inheridoc
     */
    public function filter($content)
    {
        /** @var  TemplateFilter|null $templateFilter */
        $templateFilter = $this->templateFilterFactory->create();

        return $templateFilter ? $templateFilter->filter($content) : $content;
    }
}
