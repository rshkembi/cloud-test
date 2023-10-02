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

namespace Aheadworks\Blog\Controller\Adminhtml\Comment\BuiltIn;

use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::comments_builtin';

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param CommentRepositoryInterface $commentRepository
     */
    public function __construct(
        Context $context,
        private readonly PageFactory $resultPageFactory,
        private readonly CommentRepositoryInterface $commentRepository
    ) {
        parent::__construct($context);
    }

    /**
     * Comment edit action
     *
     * @return Page|Redirect
     */
    public function execute()
    {
        $commentId = (int)$this->getRequest()->getParam('id');
        if ($commentId) {
            try {
                $this->commentRepository->getById($commentId);
            } catch (NoSuchEntityException $exception) {
                $this->messageManager->addException($exception, __('Something went wrong while editing the comment.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Aheadworks_Blog::comments_builtin')
            ->getConfig()->getTitle()->prepend(__('Edit Comment'));

        return $resultPage;
    }
}
