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

namespace Aheadworks\Blog\Block;

use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\TagInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\TagManagementInterface as TagManagement;
use Aheadworks\Blog\Block\Post\Comment\Builtin;
use Aheadworks\Blog\Block\Post\Comment\Disqus;
use Aheadworks\Blog\Model\Category as CategoryModel;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Image\Info;
use Aheadworks\Blog\Model\Post as PostModel;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Aheadworks\Blog\Model\Source\Category\Status as CategoryStatus;
use Aheadworks\Blog\Model\Source\Config\Related\BlockPosition;
use Aheadworks\Blog\Model\Source\Config\Seo\UrlType;
use Aheadworks\Blog\Model\Source\Post\SharingButtons\DisplayAt as DisplaySharingAt;
use Aheadworks\Blog\Model\Tag as TagModel;
use Aheadworks\Blog\Model\Template\FilterProvider;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Post view/list item block
 *
 * @method bool hasPost()
 * @method bool hasMode()
 * @method PostInterface getPost()
 * @method string getMode()
 * @method string getSocialIconsBlock()
 *
 * @method Post setPost(PostInterface $post)
 * @method Post setMode(string $mode)
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Post extends Template implements IdentityInterface
{
    const MODE_LIST_ITEM = 'list_item';
    const MODE_VIEW = 'view';

    /**
     * @var string
     */
    protected $_template = 'post.phtml';

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Config $config
     * @param LinkFactory $linkFactory
     * @param Url $url
     * @param FilterProvider $templateFilterProvider
     * @param FeaturedImageInfo $imageInfo
     * @param TagManagement $tagManagement
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected PostRepositoryInterface $postRepository,
        protected CategoryRepositoryInterface $categoryRepository,
        protected SearchCriteriaBuilder $searchCriteriaBuilder,
        protected Config $config,
        protected LinkFactory $linkFactory,
        protected Url $url,
        protected FilterProvider $templateFilterProvider,
        protected FeaturedImageInfo $imageInfo,
        protected TagManagement $tagManagement,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return void
     * @throws LocalizedException
     */
    protected function _construct()
    {
        parent::_construct();
        $postId = $this->getRequest()->getParam('post_id');
        if (!$this->hasPost() && $postId) {
            $this->setPost($this->postRepository->get($postId));
        }
        if (!$this->hasMode()) {
            $this->setMode(self::MODE_VIEW);
        }
    }

    /**
     * Check whether block in list item mode
     *
     * @return bool
     */
    public function isListItemMode()
    {
        return $this->getMode() == self::MODE_LIST_ITEM;
    }

    /**
     * Check whether block in view mode
     *
     * @return bool
     */
    public function isViewMode()
    {
        return $this->getMode() == self::MODE_VIEW;
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->isViewMode()) {
            /** @var \Magento\Theme\Block\Html\Breadcrumbs $breadcrumbs */
            $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
            if ($breadcrumbs) {
                $breadcrumbs->addCrumb(
                    'home',
                    [
                        'label' => __('Home'),
                        'link' => $this->_storeManager->getStore()->getBaseUrl()
                    ]
                );
                $breadcrumbs->addCrumb(
                    'blog_home',
                    [
                        'label' => $this->config->getBlogTitle(),
                        'link' => $this->url->getBlogHomeUrl(),
                    ]
                );
                if ($categoryId = $this->getRequest()->getParam('blog_category_id')) {
                    $category = $this->categoryRepository->get($categoryId);
                    $breadcrumbs->addCrumb(
                        'category_view',
                        [
                            'label' => $category->getName(),
                            'link' => $this->url->getCategoryUrl($category)
                        ]
                    );
                }
                if ($postId = $this->getRequest()->getParam('post_id')) {
                    $post = $this->postRepository->get($postId);
                    $breadcrumbs->addCrumb('post_view', ['label' => $post->getTitle()]);
                }
            }
        }
        return $this;
    }

    /**
     * Get post categories
     *
     * @return CategoryInterface[]
     */
    protected function getCategories()
    {
        $this->searchCriteriaBuilder
            ->addFilter(CategoryInterface::STATUS, CategoryStatus::ENABLED)
            ->addFilter(CategoryInterface::STORE_IDS, $this->_storeManager->getStore()->getId())
            ->addFilter(CategoryInterface::ID, $this->getPost()->getCategoryIds(), 'in');
        return $this->categoryRepository
            ->getList($this->searchCriteriaBuilder->create())
            ->getItems();
    }

    /**
     * @return bool
     */
    public function commentsEnabled()
    {
        return $this->config->isCommentsEnabled() &&
            $this->getPost()->getIsAllowComments();
    }

    /**
     * @param PostInterface $post
     * @return bool
     */
    public function showReadMoreButton(PostInterface $post)
    {
        return $this->isListItemMode() && $post->getShortContent();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSocialIconsHtml()
    {
        $displayAt = $this->config->getDisplaySharingAt();
        if (($this->isListItemMode() && in_array(DisplaySharingAt::POST_LIST, $displayAt))
            || ($this->isViewMode() && in_array(DisplaySharingAt::POST, $displayAt))
        ) {
            $block = $this->getLayout()->createBlock(
                $this->getSocialIconsBlock(),
                '',
                [
                    'data' => [
                        'post' => $this->getPost()
                    ]
                ]
            );
            return $block->toHtml();
        }
        return '';
    }

    /**
     * Retrieves array of category links html
     *
     * @return string[]
     */
    public function getCategoryLinks()
    {
        $categoryLinks = [];
        foreach ($this->getCategories() as $category) {
            /** @var Link $link */
            $link = $this->linkFactory->create();
            $categoryLinks[] = $link
                ->setHref($this->url->getCategoryUrl($category))
                ->setTitle($category->getName())
                ->setLabel($category->getName())
                ->toHtml();
        }
        return $categoryLinks;
    }

    /**
     * Retrieves comment embed code html
     *
     * @return string
     */
    public function getCommentEmbedHtml()
    {
        $commentType = $this->getCommentType();

        $html = '';
        /** @var Disqus|Builtin $commentEmbed */
        $commentEmbed = $this->getChildBlock('comment_embed')->getChildBlock($commentType . '_embed');
        if ($commentEmbed) {
            $post = $this->getPost();
            $html = $commentEmbed
                ->setPageIdentifier($post->getId())
                ->toHtml();
        }
        return $html;
    }

    /**
     * Get comment type
     *
     * @return string
     */
    public function getCommentType(): string
    {
        return $this->config->getCommentType();
    }

    /**
     * @param PostInterface $post
     * @return string
     * @throws NoSuchEntityException
     */
    public function getContent(PostInterface $post)
    {
        $content = $post->getContent();
        if ($this->isListItemMode() && $post->getShortContent()) {
            $content = $post->getShortContent();
        }
        return $this->templateFilterProvider->getFilter()
            ->setStoreId($this->_storeManager->getStore()->getId())
            ->filter($content);
    }

    /**
     * @param PostInterface $post
     * @return string
     */
    public function getPostUrl(PostInterface $post)
    {
        $categoryId = $this->getRequest()->getParam('blog_category_id');
        $storeId = $this->_storeManager->getStore()->getId();
        $canIncludeCategory = $this->config->getSeoUrlType($storeId) == UrlType::URL_INC_CATEGORY ? true : false;
        if ($canIncludeCategory && $categoryId) {
            return $this->url->getPostUrl($post, $this->categoryRepository->get($categoryId));
        }
        return $this->url->getPostUrl($post);
    }

    /**
     * @param TagInterface|string $tag
     * @return string
     */
    public function getSearchByTagUrl($tag)
    {
        return $this->url->getSearchByTagUrl($tag);
    }

    /**
     * Retrieves Related product block code html
     *
     * @param bool $viewMode
     * @param string $blockPosition
     * @return string
     */
    public function getRelatedProductHtml($viewMode, $blockPosition)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Post\RelatedProduct $postRelatedProduct */
        $postRelatedProduct = $this->getChildBlock('post_related_product');
        if ($viewMode && $postRelatedProduct && $this->config->getRelatedBlockPosition() == $blockPosition) {
            $post = $this->getPost();
            $html = $postRelatedProduct
                ->setPost($post)
                ->toHtml();
        }
        return $html;
    }

    /**
     * Retrieves related post block code html
     *
     * @param bool $viewMode
     * @return string
     */
    public function getRelatedPostHtml($viewMode)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Post\RelatedPost $relatedPostBlock */
        $relatedPostBlock = $this->getChildBlock('related_post');
        if ($viewMode) {
            $post = $this->getPost();
            $html = $relatedPostBlock
                ->setPost($post)
                ->toHtml();
        }

        return $html;
    }

    /**
     * Retrieves prev next block code html
     *
     * @param bool $viewMode
     * @return string
     */
    public function getPrevNextHtml($viewMode)
    {
        $html = '';
        /** @var \Aheadworks\Blog\Block\Post\PrevNext $prevNextBlock */
        $prevNextBlock = $this->getChildBlock('prev_next');
        if ($viewMode) {
            $post = $this->getPost();
            $html = $prevNextBlock
                ->setPost($post)
                ->toHtml();
        }

        return $html;
    }

    /**
     * Retrieve after post position
     *
     * @return string
     */
    public function getPositionAfterPost()
    {
        return BlockPosition::AFTER_POST;
    }

    /**
     * Retrieve after comments position
     *
     * @return string
     */
    public function getPositionAfterComments()
    {
        return BlockPosition::AFTER_COMMENTS;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $identities = [];

        if ($post = $this->getPost()) {
            $identities[] = PostModel::CACHE_TAG . '_' . $post->getId();
            if (is_array($post->getCategoryIds())) {
                foreach ($post->getCategoryIds() as $categoryId) {
                    $identities[] = CategoryModel::CACHE_TAG . '_' . $categoryId;
                }
            }
            foreach ($this->tagManagement->getPostTags($post) as $tag) {
                $identities[] = TagModel::CACHE_TAG . '_' . $tag->getId();
            }
        }

        return $identities;
    }

    /**
     * Get image url
     *
     * @return string
     */
    public function getAuthorImageUrl()
    {
        return $this->imageInfo->getMediaUrl() . Info::FILE_DIR . '/'
                .  $this->getPost()->getAuthor()->getImageFile();
    }

    /**
     * Retrieve author full name
     *
     * @return string
     */
    public function getAuthorFullname()
    {
        $author = $this->getPost()->getAuthor();

        return $author->getFirstname() . ' ' . $author->getLastname();
    }

    /**
     * Retrieve author image alt
     *
     * @return string
     */
    public function getAuthorImageAlt()
    {
        return __('A photo of %1', $this->getAuthorFullname());
    }

    /**
     * Retrieve author ulr
     *
     * @return string
     */
    public function getAuthorUrl()
    {
        $author = $this->getPost()->getAuthor();

        return $this->url->getAuthorUrl($author);
    }
}
