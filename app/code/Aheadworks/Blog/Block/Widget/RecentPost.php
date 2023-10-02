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
namespace Aheadworks\Blog\Block\Widget;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\ViewModel\PostImage;
use Magento\Widget\Block\BlockInterface;
use Aheadworks\Blog\Block\Post\ListingFactory;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Serialize\SerializeInterface;
use Aheadworks\Blog\Model\Serialize\Factory as SerializeFactory;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;
use Aheadworks\Blog\Block\Sidebar\Recent;

/**
 * Class RecentPost
 *
 * @package Aheadworks\Blog\Block\Widget
 */
class RecentPost extends Recent implements BlockInterface
{
    /**
     * @var string
     */
    const WIDGET_NAME_PREFIX = 'aw_blog_widget_recent_post_';

    /**
     * Path to template file in theme
     * @var string
     */
    protected $_template = 'Aheadworks_Blog::widget/recent_post/default.phtml';

    /**
     * @var SerializeInterface
     */
    private $serializer;

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     * @param ListingFactory $postListingFactory
     * @param Config $config
     * @param Url $url
     * @param SerializeFactory $serializeFactory
     * @param FeaturedImageInfo $imageInfo
     * @param PostImage $viewModel
     * @param array $data
     */
    public function __construct(
        Context $context,
        PostRepositoryInterface $postRepository,
        ListingFactory $postListingFactory,
        Config $config,
        Url $url,
        SerializeFactory $serializeFactory,
        FeaturedImageInfo $imageInfo,
        PostImage $viewModel,
        array $data = []
    ) {
        $data['view_model'] = $viewModel;
        parent::__construct($context, $postRepository, $postListingFactory, $config, $url, $imageInfo, $data);
        $this->serializer = $serializeFactory->create();
    }

    /**
     * Is ajax request or not
     *
     * @return bool
     */
    public function isAjax()
    {
        return $this->_request->isAjax();
    }

    /**
     * @inheritdoc
     */
    public function getPosts($numberToDisplay = null)
    {
        $categoryIds = $this->getData('category_ids');
        $categoryIds = empty($categoryIds) ? [] : explode(',', (string)$categoryIds);

        $this->postListing->getSearchCriteriaBuilder()->setPageSize(
            $this->getData('number_to_display')
        );
        if ($postId = $this->getRequest()->getParam('post_id')) {
            $this->postListing->getSearchCriteriaBuilder()
                ->addFilter(
                    PostInterface::ID,
                    $postId,
                    'neq'
                );
        }
        if (!empty($categoryIds)) {
            $this->postListing->getSearchCriteriaBuilder()
                ->addFilter(
                    PostInterface::CATEGORY_IDS,
                    $categoryIds
                );
        }
        return $this->postListing->getPosts();
    }

    /**
     * Checks blog is enabled or not
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->isBlogEnabled();
    }

    /**
     * Retrieve widget encode data
     *
     * @return string
     */
    public function getWidgetEncodeData()
    {
        return base64_encode(
            $this->serializer->serialize(
                [
                    'name' => $this->getNameInLayout(),
                    'number_to_display' => $this->getData('number_to_display'),
                    'are_images_disabled' => $this->getData('are_images_disabled'),
                    'category_ids' => $this->getData('category_ids'),
                    'title' => $this->getData('title'),
                    'template' => $this->getTemplate()
                ]
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getNameInLayout()
    {
        return self::WIDGET_NAME_PREFIX . parent::getNameInLayout();
    }
}
