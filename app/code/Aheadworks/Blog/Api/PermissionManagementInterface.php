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
 * Permission management service interface
 * @api
 */
interface PermissionManagementInterface
{
    /**
     * Check if post is allowed
     *
     * @param \Aheadworks\Blog\Api\Data\PostInterface $post
     * @param int $storeId
     * @param int $customerGroupId
     * @return bool
     */
    public function isPostAllowed(\Aheadworks\Blog\Api\Data\PostInterface $post, $storeId, $customerGroupId);
}
