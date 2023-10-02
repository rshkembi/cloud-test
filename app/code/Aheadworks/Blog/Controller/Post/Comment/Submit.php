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

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Data\OperationInterface as DataOperationInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface as DataProcessorInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\Result\RedirectFactory;

class Submit implements HttpPostActionInterface
{
    /**
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     * @param DataProcessorInterface $postDataProcessor
     * @param DataOperationInterface $dataOperation
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        private readonly RedirectFactory $redirectFactory,
        private readonly RequestInterface $request,
        private readonly DataProcessorInterface $postDataProcessor,
        private readonly DataOperationInterface $dataOperation,
        private readonly ManagerInterface $messageManager
    ) {
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        $postData = $this->request->getPostValue();
        $resultRedirect = $this->redirectFactory->create();

        if (!empty($postData)) {
            try {
                $preparedData = $this->postDataProcessor->process($postData);
                $this->dataOperation->execute($preparedData);
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while submitting the comment.')
                );
            }
            empty($preparedData[CommentInterface::REPLY_TO_COMMENT_ID]) ?
                $this->messageManager->addSuccessMessage( __('Your comment has been received.'))
                : $this->messageManager->addSuccessMessage( __('Your reply has been received.'));
        }

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
