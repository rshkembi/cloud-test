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

namespace Aheadworks\Blog\ViewModel\Post\Comment;

use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver;
use Aheadworks\Blog\Model\Api\SearchCriteria\CollectionProcessor\Frontend\ConfigProvider;
use Aheadworks\Blog\Model\Post\Comment\Provider as CommentProvider;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Aheadworks\Blog\Block\Post\Comment\Renderer as CommentRendererBlock;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CommentList implements ArgumentInterface
{
    public const DEFAULT_COMMENT_QTY = 5;
    public const DEFAULT_CURRENT_PAGE = 1;

    /**
     * @param CommentProvider $commentProvider
     * @param ConfigProvider $configProvider
     * @param RequestInterface $request
     * @param Json $jsonSerializer
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        private readonly CommentProvider $commentProvider,
        private readonly ConfigProvider $configProvider,
        private readonly RequestInterface $request,
        private readonly Json $jsonSerializer,
        private readonly UrlInterface $urlBuilder
    ) {
    }

    /**
     * Get comment list data
     *
     * @param $block
     * @return array
     * @throws NoSuchEntityException
     */
    public function getCommentListData($block): array
    {
        $this->getData($block);

        return $this->commentProvider->getComments($this->getData($block));
    }

    /**
     * Get search criteria
     *
     * @param Template $block
     * @return array
     */
    public function getData(Template $block): array
    {
        $data = [];
        $params = $this->request->getParams();
        $data[Resolver::POST_ID] = $this->getPostId($block);
        $data[Resolver::CURRENT_PAGE] = !isset($params['currentPage']) ? self::DEFAULT_CURRENT_PAGE : (int)$params['currentPage'];
        $data[Resolver::CHILD_COMMENT_QTY] = self::DEFAULT_COMMENT_QTY;
        if ($this->request->getParam('direction')) {
            $data[Resolver::SORT_ORDER_DIRECTION] = $this->request->getParam('direction');
        }

        return $data;
    }

    /**
     * Check if comment list is empty
     *
     * @param array|null $commentListData
     * @return bool
     */
    public function isCommentListEmpty(?array $commentListData): bool
    {
        return (empty($commentListData));
    }

    /**
     * Retrieve comment rendered HTML
     *
     * @param Template $currentBlock
     * @param string $commentRendererBlockAlias
     * @param array $commentDataRow
     * @return string
     */
    public function getCommentRenderedHtml($currentBlock, string $commentRendererBlockAlias, array $commentDataRow): string
    {
        $html = '';

        /** @var CommentRendererBlock $commentRendererBlock */
        $commentRendererBlock = $currentBlock->getChildBlock($commentRendererBlockAlias);
        if ($commentRendererBlock) {
            $commentRendererBlock->setCommentData($commentDataRow);
            $commentRendererBlock->setPageIdentifier(
                !$currentBlock->getPageIdentifier()
                    ? (int)$this->request->getParam('post_id') : $currentBlock->getPageIdentifier()
            );
            $html = $currentBlock->getChildHtml($commentRendererBlockAlias, false);
        }

        return $html;
    }

    /**
     * Get current page
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->configProvider->getGeneralCurrentPage();
    }

    /**
     * Get last page
     *
     * @return int
     */
    public function getLastPage(): int
    {
        return $this->configProvider->getGeneralLastPage();
    }

    /**
     * Get config
     *
     * @param Template $block
     * @return string
     */
    public function getConfig(Template $block): string
    {
        $config = [
            'url' => $this->urlBuilder->getUrl('aw_blog/post_comment/load'),
            'currentPage' => $this->getCurrentPage(),
            'postId' => $this->getPostId($block),
            'direction' => $this->configProvider->getDirection()
        ];

        return $this->jsonSerializer->serialize($config);
    }

    /**
     * Get post id
     *
     * @param $block
     * @return int
     */
    public function getPostId($block): int
    {
        return !$block->getPageIdentifier() ? (int)$this->request->getParam('post_id') : (int)$block->getPageIdentifier();
    }

    /**
     * Is Show button more
     *
     * @return bool
     */
    public function isShowButtonMore(): bool
    {
        return $this->getCurrentPage() < $this->getLastPage();
    }
}
