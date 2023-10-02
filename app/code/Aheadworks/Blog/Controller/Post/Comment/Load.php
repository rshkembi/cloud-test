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

namespace Aheadworks\Blog\Controller\Post\Comment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Load extends Action implements HttpGetActionInterface
{
    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($this->_request->getParam('parentCommentId')) {
            $resultPage->addHandle('aw_blog_post_comment_reply_listing');
            $block = $resultPage->getLayout()->getBlock('aw_blog.builtin.comment.reply.listing');
        } else {
            $resultPage->addHandle('aw_blog_post_comment_listing');
            $block = $resultPage->getLayout()->getBlock('aw_blog.builtin.comment.listing');
        }

        return $this->getResponse()->setBody($block->toHtml());
    }
}
