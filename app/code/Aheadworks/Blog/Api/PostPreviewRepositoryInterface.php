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

/**
 * Interface PostPreviewRepositoryInterface
 * @package Aheadworks\Blog\Api
 */
interface PostPreviewRepositoryInterface
{
    /**
     * Save post preview
     *
     * @param \Aheadworks\Blog\Api\Data\PostPreviewInterface $postPreview
     * @return \Aheadworks\Blog\Api\Data\PostPreviewInterface
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Aheadworks\Blog\Api\Data\PostPreviewInterface $postPreview);

    /**
     * Retrieve post preview
     *
     * @param int $postPreviewId
     * @return \Aheadworks\Blog\Api\Data\PostPreviewInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($postPreviewId);

    /**
     * Delete post preview by ID
     *
     * @param int $postPreviewId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($postPreviewId);

    /**
     * Delete post preview
     *
     * @param \Aheadworks\Blog\Api\Data\PostPreviewInterface $postPreview
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(\Aheadworks\Blog\Api\Data\PostPreviewInterface $postPreview);
}
