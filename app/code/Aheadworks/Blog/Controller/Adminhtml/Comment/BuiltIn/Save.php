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

use Aheadworks\Blog\Api\CommentManagementInterface;
use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Data\OperationInterface as DataOperationInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface as DataProcessorInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Aheadworks\Blog\Api\CommentRepositoryInterface;
use Aheadworks\Blog\Api\Data\CommentInterfaceFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Backend\App\Action;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Aheadworks_Blog::comments_builtin';

    /**
     * @param Context $context
     * @param CommentRepositoryInterface $commentRepository
     * @param CommentManagementInterface $commentService
     * @param CommentInterfaceFactory $commentFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataProcessorInterface $postDataProcessor
     */
    public function __construct(
        Context $context,
        private readonly DataProcessorInterface $postDataProcessor,
        private readonly DataOperationInterface $dataOperation
    ) {
        parent::__construct($context);
    }

    /**
     * Save comment
     *
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($commentData = $this->getRequest()->getPostValue()) {
            $commentData = $this->postDataProcessor->process($commentData);
            try {
                $this->dataOperation->execute($commentData);
                $this->messageManager->addSuccessMessage(__('The comment was successfully saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while saving the comment.')
                );
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
