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
 * Interface EmailSubscriberDataRowRepositoryInterface
 */
interface EmailSubscriberDataRowRepositoryInterface
{
    /**
     * Retrieve subscriber data row by id
     *
     * @param int $entityId
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById(int $entityId);

    /**
     * Save subscriber data row
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface $entity
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface $entity);

    /**
     * Delete subscriber data row
     *
     * @param \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface $entity
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowInterface $entity): bool;

    /**
     * Delete subscriber data row by id
     *
     * @param int $entityId
     * @return bool
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById(int $entityId): bool;

    /**
     * Retrieve subscriber data row list matching the specified criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Blog\Api\Data\Email\Subscriber\DataRowSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
