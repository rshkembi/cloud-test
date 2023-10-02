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

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

class ReplyParentComment implements ModifierInterface
{
    /**
     * @param RequestInterface $request
     * @param CommentRepositoryInterface $commentRepository
     * @param PostRepositoryInterface $postRepository
     * @param UrlInterface $backendUrlBuilder
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly PostRepositoryInterface $postRepository,
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
     * Modify data
     *
     * @param array $data
     * @return array
     * @throws LocalizedException
     */
    public function modifyData(array $data): array
    {
        if (!empty($data['reply_to_comment_id'])) {
            try {
                $comment = $this->commentRepository->getById((int)$data['reply_to_comment_id']);
            } catch (NoSuchEntityException $e) {
                return $data;
            }
            $this->prepareData($data, $comment);

            return $data;
        }
        if ($this->request->getActionName() === 'reply' && $this->request->getParam('id')) {
            $commentId = (int)$this->request->getParam('id');
            try {
                $comment = $this->commentRepository->getById($commentId);
                $post = $this->postRepository->get($comment->getPostId());
            } catch (NoSuchEntityException $e) {
                return $data;
            }

            $data[CommentInterface::REPLY_TO_COMMENT_ID] = $comment->getId();
            $data['post_title'] = $post->getTitle();
            $data[CommentInterface::POST_ID] = $comment->getPostId();
            $data[CommentInterface::STORE_ID] = $comment->getStoreId();
            $this->prepareData($data, $comment);
        }

        return $data;
    }

    /**
     * Retrieve content
     *
     * @param CommentInterface $comment
     * @return string|null
     */
    private function getContent(CommentInterface $comment): ?string {
        $content = $comment->getContent();
        if ($content && mb_strlen($content) > 252) {
            $content = mb_substr($content, 0, 252) . '...' ;
        }

        return $content;
    }

    /**
     * Prepare data
     *
     * @param array $data
     * @param CommentInterface $comment
     * @return array
     */
    public function prepareData(array &$data, CommentInterface $comment): array
    {
        $commentContent = $this->getContent($comment);
        $data['comment_reply_content'] = $commentContent;
        $data['comment_reply_content_label'] = $commentContent;
        $data['comment_reply_content_url'] =
            $this->backendUrlBuilder->getUrl('aw_blog_admin/comment_builtin/edit', ['id' => $comment->getId()]);

        return $data;
    }
}
