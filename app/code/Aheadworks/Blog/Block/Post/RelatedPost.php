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
namespace Aheadworks\Blog\Block\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class RelatedPost
 *
 * @method PostInterface getPost()
 * @method RelatedPost setPost(PostInterface $post)
 * @package Aheadworks\Blog\Block\Post
 */
class RelatedPost extends Template implements IdentityInterface
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @param TemplateContext $context
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param PostRepositoryInterface $postRepository
     * @param Url $url
     * @param HttpContext $httpContext
     * @param array $data
     */
    public function __construct(
        TemplateContext $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        PostRepositoryInterface $postRepository,
        Url $url,
        HttpContext $httpContext,
        array $data = []
    ) {
        $this->storeManager = $context->getStoreManager();
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->postRepository = $postRepository;
        $this->url = $url;
        $this->httpContext = $httpContext;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve related posts
     *
     * @return PostInterface[]
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getPosts()
    {
        $post = $this->postRepository->getWithRelatedPosts(
            $this->getPost()->getId(),
            $this->storeManager->getStore()->getId(),
            $this->httpContext->getValue(Context::CONTEXT_GROUP)
        );

        $relatedPosts = [];
        if ($post->getRelatedPostIds()) {
            $this->searchCriteriaBuilder->addFilter(PostInterface::ID, $post->getRelatedPostIds(), 'in');
            $relatedPosts = $this->postRepository->getList($this->searchCriteriaBuilder->create())->getItems();
        }

        return $relatedPosts;
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
     * @inheritdoc
     */
    public function getIdentities()
    {
        return [\Aheadworks\Blog\Model\Post::CACHE_TAG_LISTING];
    }
}
