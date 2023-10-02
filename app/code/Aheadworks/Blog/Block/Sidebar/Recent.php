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
namespace Aheadworks\Blog\Block\Sidebar;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Block\Post\ListingFactory ;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Magento\Framework\View\Element\Template;

/**
 * Recent posts sidebar
 *
 * @package Aheadworks\Blog\Block\Sidebar
 */
class Recent extends Template implements IdentityInterface
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var PostRepositoryInterface
     */
    protected $postRepository;

    /**
     * @var \Aheadworks\Blog\Block\Post\Listing
     */
    protected $postListing;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var FeaturedImageInfo
     */
    protected $imageInfo;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ListingFactory $postListingFactory
     * @param Config $config
     * @param Url $url
     * @param FeaturedImageInfo $imageInfo
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ListingFactory $postListingFactory,
        Config $config,
        Url $url,
        FeaturedImageInfo $imageInfo,
        array $data = []
    ) {
        $this->postRepository = $postRepository;
        $this->postListing = $postListingFactory->create();
        $this->config = $config;
        $this->url = $url;
        $this->imageInfo = $imageInfo;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve recent posts
     *
     * @param int $numberToDisplay
     * @return PostInterface[]
     */
    public function getPosts($numberToDisplay = null)
    {
        $numberToDisplay = $numberToDisplay ? : $this->config->getNumRecentPosts();
        $this->postListing->getSearchCriteriaBuilder()->setPageSize(
            $numberToDisplay
        );
        if ($postId = $this->getRequest()->getParam('post_id')) {
            $this->postListing->getSearchCriteriaBuilder()->addFilter(
                PostInterface::ID,
                $postId,
                'neq'
            );
        }
        return $this->postListing->getPosts();
    }

    /**
     * Retrieve post url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getPostUrl(PostInterface $post)
    {
        return $this->url->getPostUrl($post);
    }

    /**
     * Check is Need To Show Images
     *
     * @return bool
     */
    public function isNeedToShowImages() {
        return $this->getAreImagesDisabled() ? false : true;
    }

    /**
     * Get featured image url
     *
     * @param PostInterface $post
     * @return string
     */
    public function getFeaturedImageUrl(PostInterface $post)
    {
        return $this->imageInfo->getImageUrl($post->getFeaturedImageFile());
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

        return $this->imageInfo->getImageUrl($imageFile);
    }

    /**
     * @inheritdoc
     */
    public function getIdentities()
    {
        return [\Aheadworks\Blog\Model\Post::CACHE_TAG_LISTING];
    }
}
