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
namespace Aheadworks\Blog\Model\UrlRewrites\Cleaner\Update\Entity\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType as UrlRewriteEntityType;
use Aheadworks\Blog\Model\StoreSetOperations;
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Aheadworks\Blog\Model\UrlRewrites\Cleaner\Update\AbstractCleaner;

/**
 * Class Category
 * @package Aheadworks\Blog\Model\UrlRewrites\Cleaner\Entity\Category
 */
class Category extends AbstractCleaner
{
    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * @var StoreSetOperations
     */
    private $storeSetOperations;

    /**
     * Category constructor.
     * @param RewriteStorageInterface $rewriteStorage
     * @param StoreSetOperations $storeSetOperations
     * @param array $subordinateEntitiesCleaners
     */
    public function __construct(
        RewriteStorageInterface $rewriteStorage,
        StoreSetOperations $storeSetOperations,
        $subordinateEntitiesCleaners = []
    ) {
        parent::__construct($subordinateEntitiesCleaners);

        $this->rewriteStorage = $rewriteStorage;
        $this->storeSetOperations = $storeSetOperations;
    }

    /**
     * @inheritdoc
     * @param CategoryInterface $newEntityState
     * @param CategoryInterface $oldEntityState
     */
    protected function cleanEntityRewrites($newEntityState, $oldEntityState)
    {
        $excludedStores = $this->storeSetOperations->getDifference($oldEntityState->getStoreIds(), $newEntityState->getStoreIds());

        if (!empty($excludedStores)) {
            $this->rewriteStorage->deleteByData([
                UrlRewrite::ENTITY_TYPE => UrlRewriteEntityType::TYPE_CATEGORY,
                UrlRewrite::ENTITY_ID => $newEntityState->getId(),
                UrlRewrite::STORE_ID => $excludedStores
            ]);
        }
    }
}
