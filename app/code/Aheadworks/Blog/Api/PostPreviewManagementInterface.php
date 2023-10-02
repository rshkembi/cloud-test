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
 * Interface PostPreviewManagementInterface
 * @package Aheadworks\Blog\Api
 */
interface PostPreviewManagementInterface
{
    /**
     * Save preview data
     *
     * @param array $data
     * @return int
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function savePreviewData($data);

    /**
     * Get preview data
     *
     * @param int $key
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getPreviewData($key);

    /**
     * Withdraw(get and delete) preview data
     *
     * @param int $key
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function withdrawPreviewData($key);
}
