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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Form;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Blog\Api\Data\PostInterface;

class DataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private readonly ArrayManager $arrayManager
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
        $commentFormProviderPath = 'components/awBlogCommentFormProvider';
        $jsLayout = $this->arrayManager->merge(
            $commentFormProviderPath,
            $jsLayout,
            [
                'data' => $this->getCurrentPostData(
                    $relatedObjectList[self::POST_KEY] ?? null
                )
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve current post data for comment form
     *
     * @param PostInterface|null $post
     * @return array
     */
    private function getCurrentPostData(?PostInterface $post = null): array
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
}
