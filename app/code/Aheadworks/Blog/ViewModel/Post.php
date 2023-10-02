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

namespace Aheadworks\Blog\ViewModel;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver as CommentResolver;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Post\Author\Resolver;
use Aheadworks\Blog\Model\Post\Comment\Provider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class Post implements ArgumentInterface
{
    /**
     * Post constructor.
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param Resolver $authorResolver
     * @param Provider $commentProvider
     */
    public function __construct(
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
        private readonly Resolver $authorResolver,
        private readonly Provider $commentProvider
    ) {
    }

    /**
     * Retrieve Is Author Displayed
     *
     * @return bool
     */
    public function getIsAuthorDisplayed($post)
    {
        $storeId = $this->storeManager->getStore()->getId();

        return $this->authorResolver->resolveToDisplayAuthor($post, $storeId);
    }

    /**
     * Render Author Badge Html
     *
     * @param AbstractBlock $block
     * @param PostInterface $post
     * @return mixed
     */
    public function renderAuthorBadgeHtml($block, $post)
    {
        $authorBadgeBlock = $block->getChildBlock('aw_blog_post.author_badge');
        $author = $post->getAuthor();

        return $authorBadgeBlock ? $authorBadgeBlock->setAuthor($author)->toHtml() : '';
    }

    /**
     * Check if need to display author badge block for current post
     *
     * @param PostInterface $post
     * @return bool
     */
    public function isNeedToDisplayAuthorBadgeBlock($post)
    {
        return $post->getIsAuthorBadgeEnabled();
    }

    /**
     * Retrieves featured image html
     *
     * @param AbstractBlock $block
     * @param string $blockAlias
     * @param PostInterface|null $post
     * @return string
     */
    public function getFeaturedImageHtml($block, string $blockAlias, $post = null)
    {
        $html = '';

        /** @var \Aheadworks\Blog\Block\PostImage $imageBlock */
        $imageBlock = $block->getChildBlock($blockAlias);
        if ($imageBlock) {
            $html = $imageBlock
                ->setPost($post ?: $block->getPost())
                ->toHtml();
        }

        return $html;
    }

    /**
     * Check if featured image is loaded
     *
     * @param PostInterface $post
     * @return bool
     */
    public function isFeaturedImageLoaded($post)
    {
        return $post->getFeaturedImageFile() ? true : false;
    }

    /**
     * Check if placeholder image is loaded
     *
     * @return bool
     */
    public function isPlaceholderImageLoaded()
    {
        $storeId = $this->storeManager->getStore()->getId();

        return $this->config->getPostImagePlaceholder($storeId) ? true : false;
    }

    /**
     * Get comment count
     *
     * @param PostInterface $post
     * @return int
     * @throws NoSuchEntityException
     */
    public function getCommentsCount(PostInterface $post): int
    {
        $data[CommentResolver::POST_ID] = (int)$post->getId();

        return $this->commentProvider->getCountCommentsByPostId($data);
    }
}
