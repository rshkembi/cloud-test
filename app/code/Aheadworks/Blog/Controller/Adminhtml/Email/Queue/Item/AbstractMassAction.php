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

use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Ui\Component\MassAction\Filter;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item\Collection
    as EmailQueueItemCollection;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item\CollectionFactory
    as EmailQueueItemCollectionFactory;
use Magento\Framework\Exception\LocalizedException;

abstract class AbstractMassAction extends Action implements HttpPostActionInterface
{
    public const ADMIN_RESOURCE = 'Aheadworks_Blog::email_queue_item';

    /**
     * @param Context $context
     * @param Filter $filter
     * @param EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory
     */
    public function __construct(
        Context $context,
        private readonly Filter $filter,
        private readonly EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory
    ) {
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return Redirect
     */
    public function execute()
    {
        try {
            $emailQueueItemIdList = $this->getEmailQueueItemIdList();
            $updatedEmailQueueItemsCount = $this->performMassAction($emailQueueItemIdList);
            $this->messageManager->addSuccessMessage(
                __('A total of %1 email(s) have been updated', $updatedEmailQueueItemsCount)
            );
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this->getPreparedRedirect();
    }

    /**
     * Retrieve array of email queue item id for mass action
     *
     * @return array
     */
    protected function getEmailQueueItemIdList(): array
    {
        try {
            /** @var EmailQueueItemCollection $emailQueueItemCollection */
            $emailQueueItemCollection = $this->filter->getCollection(
                $this->emailQueueItemCollectionFactory->create()
            );
            $emailQueueItemIdList = $emailQueueItemCollection->getAllIds();
        } catch (LocalizedException $exception) {
            $emailQueueItemIdList = [];
        }

        return $emailQueueItemIdList;
    }

    /**
     * Performs mass action
     *
     * @param array $emailQueueItemIdList
     * @return int
     * @throws LocalizedException
     */
    abstract protected function performMassAction(array $emailQueueItemIdList): int;

    /**
     * Retrieve redirect to email queue grid in the current state
     *
     * @return Redirect
     */
    protected function getPreparedRedirect()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setRefererOrBaseUrl();
    }
}
