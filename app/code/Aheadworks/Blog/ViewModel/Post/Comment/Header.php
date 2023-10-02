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

use Aheadworks\Blog\Model\Post\Comment\Provider;
use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\View\Element\Template;

class Header implements ArgumentInterface
{
    public function __construct(
        private readonly Provider $commentProvider,
        private readonly RequestInterface $request
    ) {
    }

    /**
     * Get count comments
     *
     * @param Template $block
     * @return int
     * @throws NoSuchEntityException
     */
    public function getCountComments(Template $block): int
    {
        $data[Resolver::POST_ID] = $this->request->getParam('post_id') ? (int)$this->request->getParam('post_id') :
            (int)$block->getParentBlock()->getPageIdentifier();

        return $this->commentProvider->getCountCommentsByPostId($data);
    }
}
