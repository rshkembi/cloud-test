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
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Stdlib\ArrayManager;

class DataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     * @param ElementNameResolver $elementNameResolver
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
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
        $commentId = $relatedObjectList[self::COMMENT_ID_KEY] ?? null;
        $commentFormProviderPath =
            'components/awBlogCommentReplyFormProvider'
            . $this->elementNameResolver->getElementNamePostfix($commentId)
        ;
        $jsLayout = $this->arrayManager->merge(
            $commentFormProviderPath,
            $jsLayout,
            [
                'data' => array_merge_recursive(
                    $this->getCurrentPostData($relatedObjectList[self::POST_KEY] ?? null),
                    $this->getCurrentCommentData($commentId)
                )
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve current post data for comment reply form
     *
     * @param ProductInterface|null $post
     * @return array
     */
    private function getCurrentPostData($post = null): array
    {
        if (isset($post)) {
            $currentPostData = [
                'post_id' => $post->getId(),
            ];
        } else {
            $currentPostData = [];
        }

        return $currentPostData;
    }

    /**
     * Retrieve current comment data for comment reply form
     *
     * @param int|null $commentId
     * @return array
     */
    private function getCurrentCommentData(?int $commentId = null): array
    {
        if (isset($commentId)) {
            $currentCommentData = [
                'comment_id' => $commentId
            ];
        } else {
            $currentCommentData = [];
        }

        return $currentCommentData;
    }
}
