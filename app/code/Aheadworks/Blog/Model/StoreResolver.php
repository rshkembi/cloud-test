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
namespace Aheadworks\Blog\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Api\StoreWebsiteRelationInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class StoreResolver
 * @package Aheadworks\Blog\Model
 */
class StoreResolver
{
    const ALL_STORE_VIEWS = '0';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var StoreWebsiteRelationInterface
     */
    private $storeWebsiteRelation;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @param StoreManagerInterface $storeManager
     * @param StoreWebsiteRelationInterface $storeWebsiteRelation
     * @param Logger $logger
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        StoreWebsiteRelationInterface $storeWebsiteRelation,
        Logger $logger
    ) {
        $this->storeManager = $storeManager;
        $this->storeWebsiteRelation = $storeWebsiteRelation;
        $this->logger = $logger;
    }

    /**
     * Get store ids
     *
     * @param int[] $websiteIds
     * @return array
     */
    public function getStoreIds($websiteIds)
    {
        $storeIdsArrays = [];
        foreach ($websiteIds as $websiteId) {
            $storeIdsArrays[] = $this->storeWebsiteRelation->getStoreByWebsiteId($websiteId);
        }

        return array_unique(array_merge(...$storeIdsArrays));
    }

    /**
     * Get all store ids
     *
     * @return int[]
     */
    public function getAllStoreIds()
    {
        return array_keys($this->storeManager->getStores());
    }

    /**
     * Retrieve website id list by store id list
     *
     * @param array $storeIdList
     * @return array
     */
    public function getWebsiteIdListByStoreIdList($storeIdList)
    {
        $websiteIdList = [];
        foreach ($storeIdList as $storeId) {
            if ($storeId == self::ALL_STORE_VIEWS) {
                $websiteIdList = array_keys($this->storeManager->getWebsites());
                break;
            }

            try {
                $websiteIdList[] = $this->storeManager->getStore($storeId)->getWebsiteId();
            } catch (NoSuchEntityException $exception) {
                $this->logger->warning($exception->getMessage());
            }
        }

        return array_unique($websiteIdList);
    }

    /**
     * Retrieve current store
     *
     * @return StoreInterface|null
     */
    public function getCurrentStore(): ?StoreInterface
    {
        try {
            $currentStore = $this->storeManager->getStore();
        } catch (LocalizedException $exception) {
            $currentStore = null;
        }
        return $currentStore;
    }

    /**
     * Retrieve specific store by id
     *
     * @param int $storeId
     * @return StoreInterface|null
     */
    public function getStore(int $storeId): ?StoreInterface
    {
        try {
            $store = $this->storeManager->getStore($storeId);
        } catch (LocalizedException $exception) {
            $store = null;
        }
        return $store;
    }
}
