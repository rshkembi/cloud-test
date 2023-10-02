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

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Data\OperationInterface as DataOperationInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface as DataProcessorInterface;
use Aheadworks\Blog\Ui\DataProvider\CommentDataProvider;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NotFoundException;

class Create extends Action implements HttpPostActionInterface
{
    const ADMIN_RESOURCE = 'Aheadworks_Blog::comments_builtin';

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param DataProcessorInterface $postDataProcessor
     * @param DataOperationInterface $dataOperation
     */
    public function __construct(
        Context $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly DataProcessorInterface $postDataProcessor,
        private readonly DataOperationInterface $dataOperation
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return Redirect
     * @throws NotFoundException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($postData = $this->getRequest()->getPostValue()) {
            try {
                $preparedData = $this->postDataProcessor->process($postData);

                /** @var CommentInterface $comment */
                $this->dataOperation->execute($preparedData);

                $this->dataPersistor->clear(CommentDataProvider::DATA_PERSISTOR_KEY);
                $this->messageManager->addSuccessMessage(__('The comment was successfully created'));

                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while creating the comment')
                );
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
