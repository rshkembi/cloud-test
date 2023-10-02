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
namespace Aheadworks\Blog\ViewModel\Post;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\StructuredData\ProviderInterface as PostStructuredDataProviderInterface;

class StructuredData implements ArgumentInterface
{
    /**
     * @param RequestInterface $request
     * @param SerializerInterface $serializer
     * @param PostRepositoryInterface $postRepository
     * @param PostStructuredDataProviderInterface $postStructuredDataProvider
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly SerializerInterface $serializer,
        private readonly PostRepositoryInterface $postRepository,
        private readonly PostStructuredDataProviderInterface $postStructuredDataProvider
    ) {
    }

    /**
     * Retrieve structured data array for current post
     *
     * @return string
     */
    public function getStructuredDataForCurrentPost(): string
    {
        $data = [];
        $currentPost = $this->getCurrentPost();
        if ($currentPost) {
            $data = $this->postStructuredDataProvider->getData($currentPost);
        }
        return $this->serializer->serialize($data);
    }

    /**
     * Retrieve current post
     *
     * @return PostInterface|null
     */
    protected function getCurrentPost()
    {
        $postId = $this->request->getParam('post_id');
        try {
            $currentPost = $this->postRepository->get($postId);
        } catch (\Exception $e) {
            $currentPost = null;
        }
        return $currentPost;
    }
}
