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
namespace Aheadworks\Blog\Model\Resolver;

use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\Data\CategoryInterface;
use Magento\Store\Model\Store;

/**
 * Class StoreIds
 *
 * @package Aheadworks\Blog\Model\Resolver
 */
class StoreIds
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
    }

    /**
     * Retrieve array of entity store ids
     *
     * @param PostInterface|CategoryInterface $entity
     * @return array
     */
    public function getStoreIds($entity)
    {
        $storeIds = [];
        if ($entity instanceof PostInterface
            || $entity instanceof CategoryInterface
        ) {
            $storeIds = is_array($entity->getStoreIds()) ? $entity->getStoreIds() : [];
            if (count($storeIds) == 1) {
                $storeId = reset($storeIds);
                if ($storeId == Store::DEFAULT_STORE_ID) {
                    $storeIds = array_keys($this->storeManager->getStores());
                }
            }
        }
        return $storeIds;
    }
}
