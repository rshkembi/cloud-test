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

namespace Aheadworks\Blog\Model\Post\Resolver\Page;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Post\ResolverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;

class CommentListing implements ResolverInterface
{
    /**
     * @param RequestInterface $request
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly PostRepositoryInterface $postRepository
    ) {
    }

    /**
     * Extract post for current page type
     *
     * @return PostInterface|null
     */
    public function getCurrentPost(): ?PostInterface
    {
        $postId = $this->request->getParam('post_id');
        try {
            $currentPost = $this->postRepository->get($postId);
        } catch (LocalizedException $exception) {
            $currentPost = null;
        }
        return $currentPost;
    }
}
