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
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Aheadworks\Blog\Model\ResourceModel\Email\Queue\Item\CollectionFactory
    as EmailQueueItemCollectionFactory;
use Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface as EmailQueueItemInterface;

class MassSend extends AbstractMassAction
{
    /**
     * @param Context $context
     * @param Filter $filter
     * @param EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory
     * @param DataOperationInterface $dataOperation
     */
    public function __construct(
        Context $context,
        Filter $filter,
        EmailQueueItemCollectionFactory $emailQueueItemCollectionFactory,
        private readonly DataOperationInterface $dataOperation
    ) {
        parent::__construct(
            $context,
            $filter,
            $emailQueueItemCollectionFactory
        );
    }

    /**
     * Performs mass action
     *
     * @param array $emailQueueItemIdList
     * @return int
     * @throws LocalizedException
     */
    protected function performMassAction(array $emailQueueItemIdList): int
    {
        $updatedEmailQueueItemsCount = 0;

        foreach ($emailQueueItemIdList as $emailQueueItemId) {
            $this->dataOperation->execute(
                [
                    EmailQueueItemInterface::ENTITY_ID => $emailQueueItemId,
                ]
            );
            $updatedEmailQueueItemsCount++;
        }

        return $updatedEmailQueueItemsCount;
    }
}
