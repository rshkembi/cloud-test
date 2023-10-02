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

use Magento\Widget\Model\Template\FilterEmulate;

/**
 * Class Widget
 * @package Aheadworks\BlogGraphQl\Model\TemplateFilter
 */
class Widget implements FilterInterface
{
    /**
     * @var FilterEmulate
     */
    private $filterEmulate;

    /**
     * @param FilterEmulate $filterEmulate
     */
    public function __construct(
        FilterEmulate $filterEmulate
    ) {
        $this->filterEmulate = $filterEmulate;
    }

    /**
     * @inheridoc
     */
    public function filter($content)
    {
        return $this->filterEmulate->filter($content);
    }
}
