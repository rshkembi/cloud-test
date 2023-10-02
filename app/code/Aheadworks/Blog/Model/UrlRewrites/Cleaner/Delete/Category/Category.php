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
namespace Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType as UrlRewriteEntityType;
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\AbstractCleaner;

/**
 * Class Category
 * @package Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\Category
 */
class Category extends AbstractCleaner
{
    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * Category constructor.
     * @param RewriteStorageInterface $rewriteStorage
     * @param array $subordinateEntitiesCleaners
     */
    public function __construct(
        RewriteStorageInterface $rewriteStorage,
        $subordinateEntitiesCleaners = []
    ) {
        $this->rewriteStorage = $rewriteStorage;

        parent::__construct($subordinateEntitiesCleaners);
    }

    /**
     * @inheritdoc
     * @param CategoryInterface $deletedEntity
     */
    protected function cleanEntityRewrites($deletedEntity)
    {
        $this->rewriteStorage->deleteByData([
            UrlRewrite::ENTITY_TYPE => UrlRewriteEntityType::TYPE_CATEGORY,
            UrlRewrite::ENTITY_ID => $deletedEntity->getId()
        ]);
    }
}
