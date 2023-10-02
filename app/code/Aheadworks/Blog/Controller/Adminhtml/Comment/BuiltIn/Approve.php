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

use Aheadworks\Blog\Model\Service\CommentService;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Aheadworks\Blog\Model\Source\Comment\Status;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;

class Approve extends Action implements HttpGetActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::comments_builtin';

    /**
     * @param Context $context
     * @param CommentService $commentService
     */
    public function __construct(
        Context $context,
        private readonly CommentService $commentService
    ) {
        parent::__construct($context);
    }

    /**
     * Comment change status action
     *
     * @return Page|Redirect
     * @throws CouldNotSaveException
     */
    public function execute()
    {
        $commentId = (int)$this->getRequest()->getParam('id');

        if ($this->commentService->changeStatus($commentId, Status::APPROVE)) {
            $this->messageManager->addSuccessMessage(__('A record was changed.'));
        } else {
            $this->messageManager->addSuccessMessage(__('No record was changed.'));
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('*/*/');

        return $resultRedirect;
    }
}
