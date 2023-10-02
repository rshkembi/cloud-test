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
namespace Aheadworks\Blog\Block;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\CategoryRepositoryInterface;
use Aheadworks\Blog\Api\TagRepositoryInterface;
use Aheadworks\Blog\Block\Html\Pager;
use Aheadworks\Blog\Block\Post as PostBlock;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Aheadworks\Blog\ViewModel\Category\Details;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Category\Breadcrumb\DataProvider;

/**
 * List of posts block
 * @package Aheadworks\Blog\Block
 */
class PostList extends \Magento\Framework\View\Element\Template implements IdentityInterface
{
    /**
     * @var Post\Listing
     */
    protected $postListing;

    /**
     * @var CategoryRepositoryInterface
     */
    protected $categoryRepository;

    /**
     * @var TagRepositoryInterface
     */
    protected $tagRepository;

    /**
     * @var Url
     */
    protected $url;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var DataProvider
     */
    protected $dataProvider;

    /**
     * @var Details
     */
    protected $categoryViewModel;

    /**
     * @param Context $context
     * @param DataProvider $dataProvider
     * @param Post\ListingFactory $postListingFactory
     * @param CategoryRepositoryInterface $categoryRepository
     * @param TagRepositoryInterface $tagRepository
     * @param Details $categoryViewModel
     * @param Url $url
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataProvider $dataProvider,
        Post\ListingFactory $postListingFactory,
        CategoryRepositoryInterface $categoryRepository,
        TagRepositoryInterface $tagRepository,
        Details $categoryViewModel,
        Url $url,
        Config $config,
        array $data = []
    ) {
        $this->postListing = $postListingFactory->create();
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->url = $url;
        $this->config = $config;
        $this->dataProvider = $dataProvider;
        $this->categoryViewModel = $categoryViewModel;
        parent::__construct($context, $data);
    }

    /**
     * @return PostInterface[]
     */
    public function getPosts()
    {
        return $this->postListing->getPosts();
    }

    /**
     * Check need render block HTML
     *
     * @return bool
     */
    public function isNeedRenderHtml()
    {
        return !$this->isCategoryPage() ||
            ($this->categoryViewModel->isPostOnlyMode($this->categoryViewModel->getCurrentCategory())
                || $this->categoryViewModel->isMixedMode($this->categoryViewModel->getCurrentCategory()));
    }

    /**
     * Check is category page
     *
     * @return bool
     */
    public function isCategoryPage()
    {
        return (bool)$this->categoryViewModel->getCurrentCategory();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->applyPagination();

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

            $tagId = (int)$this->getRequest()->getParam('tag_id');
            $categoryId = (int)$this->getRequest()->getParam('blog_category_id');

            $blogTitle = $this->config->getBlogTitle();
            if (!$tagId && !$categoryId) {
                $breadcrumbs->addCrumb('blog_home', ['label' => $blogTitle]);
            } else {
                $breadcrumbs->addCrumb(
                    'blog_home',
                    [
                        'label' => $blogTitle,
                        'link' => $this->url->getBlogHomeUrl(),
                    ]
                );
                if ($tagId) {
                    $tag = $this->tagRepository->get($tagId);
                    $breadcrumbs->addCrumb(
                        'search_by_tag',
                        ['label' => __("Tagged with '%1'", $tag->getName())]
                    );
                }
                if ($categoryId) {
                    $category = $this->categoryRepository->get($categoryId);
                    $breadcrumbPath = $this->dataProvider->getBreadcrumbPath($category);
                    foreach ($breadcrumbPath as $key => $crumbInfo) {
                        $breadcrumbs->addCrumb('category_view_' . $key, $crumbInfo);
                    }
                }
            }
        }
        return $this;
    }

    /**
     * Retrieves items list html
     *
     * @param string $blockAlias
     * @param PostInterface $post
     * @return string
     */
    public function getItemHtml(string $blockAlias, PostInterface $post)
    {
        $html = '';

        /** @var PostBlock $block */
        $block = $this->getChildBlock($blockAlias);
        if ($block) {
            $block->setPost($post);
            $html = $block->toHtml();
        }

        return $html;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getPosts() as $post) {
            $identities = [\Aheadworks\Blog\Model\Post::CACHE_TAG . '_' . $post->getId()];
        }
        if ($categoryId = (int)$this->getRequest()->getParam('blog_category_id')) {
            $identities = [\Aheadworks\Blog\Model\Category::CACHE_TAG . '_' . $categoryId];
        }
        if ($tagId = (int)$this->getRequest()->getParam('tag_id')) {
            $identities = [\Aheadworks\Blog\Model\Tag::CACHE_TAG . '_'
                . $this->tagRepository->get($tagId)->getName()];
        }
        if (!$categoryId && !$tagId) {
            $identities[] = \Aheadworks\Blog\Model\Post::CACHE_TAG_LISTING;
        }
        return $identities;
    }

    /**
     * Apply pagination if needed
     */
    protected function applyPagination()
    {
        if ($this->isNeedPagination()) {
            /** @var Pager $pager */
            $pager = $this->getChildBlock('pager');
            if ($pager) {
                $pager
                    ->setPath(trim($this->getRequest()->getPathInfo(), '/'))
                    ->setLimit($this->config->getNumPostsPerPage());
                $this->postListing->applyPagination($pager);
            }
        }
    }

    /**
     * Check if there is at least one post, in this case pagination can be used
     *
     * @return int
     */
    private function isNeedPagination()
    {
        $this->postListing->getSearchCriteriaBuilder()->setPageSize(1);
        return count($this->getPosts());
    }
}
