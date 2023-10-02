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

namespace Aheadworks\Blog\Ui\DataProvider\Modifier\Comment;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class PostId implements ModifierInterface
{
    /**
     * @param UrlInterface $backendUrlBuilder
     */
    public function __construct(
        private readonly UrlInterface $backendUrlBuilder
    ) {
    }

    /**
     * Modify meta
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        return $meta;
    }

    /**
     * Modify post id
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        $postId = $data[CommentInterface::POST_ID] ?? null;
        $postTitle =  $data['post_title'] ?? null;
        if ($postId) {
            $data[CommentInterface::POST_ID . '_label'] = $postTitle;
            $data[CommentInterface::POST_ID . '_url'] =
                $this->backendUrlBuilder->getUrl('aw_blog_admin/post/edit', ['id' => $postId]);
        }


        return $data;
    }
}
