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
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Post\Author\Resolver;
use Aheadworks\Blog\Model\Post\Comment\Provider;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PostImage
 */
class PostImage extends Post
{
    const DEFAULT_PLACEHOLDER_DIR = 'aw_blog/post/placeholder';

    /**
     * @param FeaturedImageInfo $imageInfo
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     * @param Resolver $authorResolver
     * @param Provider $commentProvider
     */
    public function __construct(
        private readonly FeaturedImageInfo $imageInfo,
        private readonly Config $config,
        private readonly StoreManagerInterface $storeManager,
        Resolver $authorResolver,
        Provider $commentProvider
    ) {
        parent::__construct($config, $this->storeManager, $authorResolver, $commentProvider);
    }

    /**
     * Get featured image url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getFeaturedImageUrl(PostInterface $post)
    {
        return $this->imageInfo->getImageUrl($post->getFeaturedImageFile()) ?: $this->getPlaceHolderImageUrl();
    }

    /**
     * Retrieve placeholder image url
     *
     * @return bool|string
     */
    public function getPlaceHolderImageUrl()
    {
        return $this->imageInfo->getImageUrl($this->getPlaceholderImageFile());
    }

    /**
     * Retrieve placeholder image file
     *
     * @return string
     */
    public function getPlaceholderImageFile()
    {
        return self::DEFAULT_PLACEHOLDER_DIR . '/' . $this->config->getPostImagePlaceholder();
    }

    /**
     * Get featured image mobile url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getFeaturedImageMobileUrl(PostInterface $post)
    {
        $imageFile = $post->getFeaturedImageMobileFile() ?: $post->getFeaturedImageFile();

        return $this->imageInfo->getImageUrl($imageFile) ?: $this->getPlaceHolderImageUrl();
    }

    /**
     * Retrieve Featured Image Alt
     *
     * @param PostInterface $post
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFeaturedImageAlt($post)
    {
        $storeId = $this->storeManager->getStore()->getId();

        return $post->getFeaturedImageAlt() ?: $this->config->getPostImagePlaceholderAltText($storeId);
    }
}
