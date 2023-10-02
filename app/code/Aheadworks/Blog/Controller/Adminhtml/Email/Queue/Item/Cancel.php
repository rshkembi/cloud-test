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

namespace Aheadworks\Blog\Controller\Adminhtml\Email\Queue\Item;

use Aheadworks\Blog\Model\Data\OperationInterface as DataOperationInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Cancel extends Action implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Aheadworks_Blog::email_queue_item';

    /**
     * @param Context $context
     * @param DataOperationInterface $dataOperation
     */
    public function __construct(
        Context $context,
        private readonly DataOperationInterface $dataOperation
    ) {
        parent::__construct($context);
    }

    /**
     * Execute cancel action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($postData = $this->getRequest()->getParams()) {
            try {
                $this->dataOperation->execute($postData);
                $this->messageManager->addSuccessMessage(
                    __('Email was successfully cancelled.')
                );
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage(
                    $exception->getMessage()
                );
            } catch (\Exception $exception) {
                $this->messageManager->addExceptionMessage(
                    $exception,
                    __('Something went wrong while cancelling the email.')
                );
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
