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

use Aheadworks\Blog\Model\Api\SearchCriteria\CollectionProcessor\Frontend\ConfigProvider;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;

class Sorter implements ArgumentInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ConfigProvider $configProvider,
        private readonly Json $jsonSerializer,
        private readonly UrlInterface $urlBuilder
    ) {
    }

    /**
     * Get widget options json
     *
     * @param Template $block
     * @return string
     */
    public function getWidgetOptionsJson(Template $block): string
    {
        $config = [
            'url' => $this->urlBuilder->getUrl('aw_blog/post_comment/load'),
            'postId' => $this->request->getParam('post_id') ?
                $this->request->getParam('post_id') : $block->getParentBlock()->getParentBlock()->getPageIdentifier(),
            'currentPage' => $this->configProvider->getGeneralCurrentPage(),
            'direction' => $this->configProvider->getDirection() ?? SortOrder::SORT_DESC
        ];

        return $this->jsonSerializer->serialize($config);
    }
}
