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

namespace Aheadworks\Blog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Layout\LayoutProcessorProvider;
use Aheadworks\Blog\Model\Config;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;

/**
 * Class Base
 *
 * @method string getDataRoleAttributeValue()
 * @method string getClassAttributeValue()
 * @method string getDataBindScopeValue()
 */
class Base extends Template
{
    /**
     * @param Context $context
     * @param LayoutProcessorProvider $layoutProcessorProvider
     * @param Config $config
     * @param HttpContext $httpContext
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected readonly LayoutProcessorProvider $layoutProcessorProvider,
        protected readonly Config $config,
        protected readonly HttpContext $httpContext,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) && is_array($data['jsLayout'])
            ? $data['jsLayout']
            : [];
    }

    /**
     * Retrieve serialized JS layout configuration ready to use in template
     *
     * @return string
     */
    public function getJsLayout()
    {
        $relatedObjectList = $this->getRelatedObjectList();

        foreach ($this->layoutProcessorProvider->getLayoutProcessors() as $layoutProcessor) {
            $this->jsLayout = $layoutProcessor->process($this->jsLayout, $relatedObjectList);
        }

        return parent::getJsLayout();
    }

    /**
     * Retrieve list of related objects
     *
     * @return array
     */
    protected function getRelatedObjectList(): array
    {
        return [];
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->isNeedToDisplayBlock()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Check if need to display block
     *
     * @return bool
     */
    protected function isNeedToDisplayBlock(): bool
    {
        return $this->config->isCommentsEnabled();
    }

    /**
     * Is loggedIn
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }
}
