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
 * Comments service interface
 * @api
 */
interface CommentsServiceInterface
{
    /**
     * Retrieve total published comments count for a post
     *
     * @param int $postId
     * @param int $storeId
     * @return int
     */
    public function getPublishedCommNum($postId, $storeId);

    /**
     * Retrieve published comments count for posts
     *
     * @param int[] $postIds
     * @param int $storeId
     * @return int[]
     */
    public function getPublishedCommNumBundle($postIds, $storeId);

    /**
     * Retrieve new comments count for posts
     *
     * @param int[] $postIds
     * @param int $storeId
     * @return int[]
     */
    public function getNewCommNumBundle($postIds, $storeId);

    /**
     * Retrieve moderate comments url
     *
     * @param int|null $websiteId
     * @return string
     */
    public function getModerateUrl($websiteId = null);
}
