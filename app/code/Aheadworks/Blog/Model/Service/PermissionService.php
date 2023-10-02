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
namespace Aheadworks\Blog\Model\Service;

use Aheadworks\Blog\Api\PermissionManagementInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Post\Permission as PostPermission;

/**
 * Class PermissionService
 * @package Aheadworks\Blog\Model\Service
 */
class PermissionService implements PermissionManagementInterface
{
    /**
     * @var PostPermission
     */
    private $postPermission;

    /**
     * @param PostPermission $postPermission
     */
    public function __construct(
        PostPermission $postPermission
    ) {
        $this->postPermission = $postPermission;
    }

    /**
     * {@inheritdoc}
     */
    public function isPostAllowed(PostInterface $post, $storeId, $customerGroupId)
    {
        return $this->postPermission->isPostAllowed($post, $storeId, $customerGroupId);
    }
}
