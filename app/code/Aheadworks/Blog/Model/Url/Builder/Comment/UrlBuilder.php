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

namespace Aheadworks\Blog\Model\Url\Builder\Comment;

use Aheadworks\Blog\Api\CommentsServiceInterface;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\UrlInterface;

class UrlBuilder
{
    /**
     * @param Config $config
     * @param UrlInterface $backendUrlBuilder
     * @param CommentsServiceInterface $commentsService
     */
    public function __construct(
        private readonly Config $config,
        private readonly UrlInterface $backendUrlBuilder,
        private readonly CommentsServiceInterface $commentsService,
    ) {
    }

    /**
     * Get comment Url
     *
     * @return string
     */
    public function getCommentUrl(): string
   {
       if ($this->config->getCommentType() === 'disqus') {
           return $this->commentsService->getModerateUrl();
       }

       return $this->backendUrlBuilder->getUrl('aw_blog_admin/comment_builtin/index');
   }
}
