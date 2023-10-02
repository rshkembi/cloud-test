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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\VisibilityButton;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\BuiltinConfig;
use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\ElementNameUpdater\ElementNameResolver;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Api\Data\StoreInterface;

class MetadataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     * @param ElementNameResolver $elementNameResolver
     * @param HttpContext $httpContext
     * @param BuiltinConfig $builtinConfig
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly ElementNameResolver $elementNameResolver,
        private readonly HttpContext $httpContext,
        private readonly BuiltinConfig $builtinConfig
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array$relatedObjectList = []): array
    {
        $commentId = $relatedObjectList[self::COMMENT_ID_KEY] ?? null;

        $visibilityButtonComponentPath =
            'components/awBlogCommentReplyFormVisibilityButton'
            . $this->elementNameResolver->getElementNamePostfix(
                $commentId
            )
        ;
        $jsLayout = $this->arrayManager->merge(
            $visibilityButtonComponentPath,
            $jsLayout,
            $this->getMetaData(
                $commentId,
                $relatedObjectList[self::STORE_KEY] ?? null,
                $relatedObjectList[self::POST_KEY] ?? null
            )
        );

        return $jsLayout;
    }

    /**
     * Retrieve additional metadata for component
     *
     * @param int|null $commentId
     * @param StoreInterface|null $store
     * @param PostInterface|null $post
     * @return array
     */
    private function getMetaData(?int $commentId, ?StoreInterface $store, ?PostInterface $post): array
    {
        $currentStoreId = (isset($store)) ? (int)$store->getId() : null;
        $currentPostId = (isset($post)) ? $post->getId() : null;
        $isCustomerLoggedIn = $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
        $isNeedToRenderForm = !$isCustomerLoggedIn
            ? $this->builtinConfig->isAllowGuestComments($currentStoreId) : $isCustomerLoggedIn;

        return [
            'commentId' => $commentId,
            'postId' => $currentPostId,
            'visible' => $isNeedToRenderForm,
            'isNeedToRenderForm' => $isNeedToRenderForm
        ];
    }
}
