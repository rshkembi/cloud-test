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
 * Category management service interface
 * @api
 */
interface CategoryManagementInterface
{
    /**
     * Retrieve category child categories
     *
     * @param int $categoryId
     * @param int $storeId
     * @param int|array $status
     * @return \Aheadworks\Blog\Api\Data\CategoryInterface[]|null
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getChildCategories($categoryId, $storeId, $status);
}
