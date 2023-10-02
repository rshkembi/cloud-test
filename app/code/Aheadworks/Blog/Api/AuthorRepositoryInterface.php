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
namespace Aheadworks\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

/**
 * Interface AuthorRepositoryInterface
 * @package Aheadworks\Blog\Api
 */
interface AuthorRepositoryInterface
{
    /**
     * Save author
     *
     * @param \Aheadworks\Blog\Api\Data\AuthorInterface $author
     * @return \Aheadworks\Blog\Api\Data\AuthorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Blog\Api\Data\AuthorInterface $author);

    /**
     * Retrieve author
     *
     * @param int $authorId
     * @return \Aheadworks\Blog\Api\Data\AuthorInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($authorId);

    /**
     * Retrieve authors matching the specified criteria
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Aheadworks\Blog\Api\Data\AuthorSearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Delete author
     *
     * @param \Aheadworks\Blog\Api\Data\AuthorInterface $author
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Aheadworks\Blog\Api\Data\AuthorInterface $author);

    /**
     * Delete author by ID
     *
     * @param int $authorId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($authorId);
}
