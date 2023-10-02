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
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\AbstractCleaner;
use Aheadworks\Blog\Model\UrlRewrites\Finder\Post as PostRewritesFinder;

/**
 * Class Posts
 * @package Aheadworks\Blog\Model\UrlRewrites\Cleaner\Delete\Category
 */
class Posts extends AbstractCleaner
{
    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * @var PostRewritesFinder
     */
    private $postRewritesFinder;

    /**
     * Posts constructor.
     * @param RewriteStorageInterface $rewriteStorage
     * @param PostRewritesFinder $postRewritesFinder
     * @param array $subordinateEntitiesCleaners
     */
    public function __construct(
        RewriteStorageInterface $rewriteStorage,
        PostRewritesFinder $postRewritesFinder,
        $subordinateEntitiesCleaners = []
    ) {
        $this->rewriteStorage = $rewriteStorage;
        $this->postRewritesFinder = $postRewritesFinder;

        parent::__construct($subordinateEntitiesCleaners);
    }


    /**
     * @inheritdoc
     * @param CategoryInterface $deletedEntity
     */
    protected function cleanEntityRewrites($deletedEntity)
    {
        $rewriteIdsToDelete = [];

        foreach ($this->postRewritesFinder->getCategoryPostsRewrites($deletedEntity) as $postRewrite) {
            $rewriteIdsToDelete[] = $postRewrite->getUrlRewriteId();
        }

        if (!empty($rewriteIdsToDelete)) {
            $this->rewriteStorage->deleteByData([
                UrlRewrite::URL_REWRITE_ID => $rewriteIdsToDelete
            ]);
        }
    }
}
