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

namespace Aheadworks\Blog\Block\Post\Comment;

use Aheadworks\Blog\Block\Base as BaseBlock;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Layout\LayoutProcessorProvider;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Post\ResolverInterface as PostResolverInterface;
use Aheadworks\Blog\Model\StoreResolver;
use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;

class Form extends BaseBlock
{
    /**
     * @param Context $context
     * @param LayoutProcessorProvider $layoutProcessorProvider
     * @param Config $config
     * @param HttpContext $httpContext
     * @param PostResolverInterface $postResolver
     * @param StoreResolver $storeResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayoutProcessorProvider $layoutProcessorProvider,
        Config $config,
        HttpContext $httpContext,
        private readonly PostResolverInterface $postResolver,
        private readonly StoreResolver $storeResolver,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $layoutProcessorProvider,
            $config,
            $httpContext,
            $data
        );
    }

    /**
     * Retrieve list of related objects
     *
     * @return array
     */
    protected function getRelatedObjectList(): array
    {
        return [
            LayoutProcessorInterface::POST_KEY => $this->postResolver->getCurrentPost(),
            LayoutProcessorInterface::STORE_KEY => $this->storeResolver->getCurrentStore(),
        ];
    }
}
