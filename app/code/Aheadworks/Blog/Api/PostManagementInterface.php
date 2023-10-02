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

use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Post management service interface
 * @api
 */
interface PostManagementInterface
{
    /**
     * Retrieve previous post
     *
     * @param PostInterface $post
     * @param int $storeId
     * @param int $customerGroupId
     * @return \Aheadworks\Blog\Api\Data\PostInterface[]|null
     */
    public function getPrevPost($post, $storeId, $customerGroupId);

    /**
     * Retrieve next post
     *
     * @param PostInterface $post
     * @param int $storeId
     * @param int $customerGroupId
     * @return \Aheadworks\Blog\Api\Data\PostInterface[]|null
     */
    public function getNextPost($post, $storeId, $customerGroupId);

    /**
     * Retrieve related post
     *
     * @param PostInterface $post
     * @param int $storeId
     * @param int $customerGroupId
     * @return \Aheadworks\Blog\Api\Data\PostInterface[]|null
     */
    public function getRelatedPosts($post, $storeId, $customerGroupId);
}
