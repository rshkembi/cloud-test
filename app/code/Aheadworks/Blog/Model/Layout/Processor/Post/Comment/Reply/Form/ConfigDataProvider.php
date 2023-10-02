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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\ElementNameUpdater\ElementNameResolver;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\ArrayManager;

class ConfigDataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     * @param HttpContext $httpContext
     * @param ElementNameResolver $elementNameResolver
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly HttpContext $httpContext,
        private readonly ElementNameResolver $elementNameResolver
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $commentFormProviderPath =
            'components/awBlogCommentReplyConfigProvider'
            . $this->elementNameResolver->getElementNamePostfix(
                $relatedObjectList[self::COMMENT_ID_KEY] ?? null
            )
        ;
        $jsLayout = $this->arrayManager->merge(
            $commentFormProviderPath,
            $jsLayout,
            [
                'data' => $this->getConfigData()
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve config data
     *
     * @return array
     */
    protected function getConfigData(): array
    {
        return [
            'is_customer_logged_in' =>
                $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH),
        ];
    }
}
