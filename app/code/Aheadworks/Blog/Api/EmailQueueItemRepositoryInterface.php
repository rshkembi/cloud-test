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

namespace Aheadworks\Blog\Api;

/**
 * Interface EmailQueueItemRepositoryInterface
 *
 * @api
 */
interface EmailQueueItemRepositoryInterface
{
    /**
     * Retrieve email queue item by id
     *
     * @param int $emailQueueItemId
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($emailQueueItemId);

    /**
     * Save email queue item
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem);

    /**
     * Delete email queue item
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Aheadworks\Blog\Api\Data\Email\Queue\ItemInterface $emailQueueItem);

    /**
     * Delete email queue item by id
     *
     * @param int $emailQueueItemId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($emailQueueItemId);

    /**
     * Retrieve email queue item list matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Blog\Api\Data\Email\Queue\ItemSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
