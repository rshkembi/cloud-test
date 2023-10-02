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
namespace Aheadworks\Blog\Model\UrlRewrites\Finder;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Source\UrlRewrite\EntityType as UrlRewriteEntityType;
use Aheadworks\Blog\Model\Source\UrlRewrite\Metadata;
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

/**
 * Class Post
 * @package Aheadworks\Blog\Model\UrlRewrites\Finder
 */
class Post
{
    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * Post constructor.
     * @param RewriteStorageInterface $rewriteStorage
     * @param array $subordinateEntitiesCleaners
     */
    public function __construct(
        RewriteStorageInterface $rewriteStorage
    ) {
        $this->rewriteStorage = $rewriteStorage;
    }

    /**
     * Returns post rewrites for category on stores
     *
     * @param CategoryInterface$category
     * @param int[] $storeIds
     * @return UrlRewrite[]
     */
    public function getCategoryPostsRewrites($category, $storeIds = [])
   {
       $findByData = [
           UrlRewrite::ENTITY_TYPE => UrlRewriteEntityType::TYPE_POST
       ];
       if (!empty($storeIds)) {
           $findByData[UrlRewrite::STORE_ID] = $storeIds;
       }

       /** @var UrlRewrite[] $allPostRewrites */
       $allPostRewrites = $this->rewriteStorage->findAllByData($findByData);

       $result = [];
       foreach ($allPostRewrites as $postRewrite) {
           $postCategoryId = !empty($postRewrite->getMetadata()[Metadata::REWRITE_METADATA_POST_CATEGORY])
               ? $postRewrite->getMetadata()[Metadata::REWRITE_METADATA_POST_CATEGORY]
               : null;

           if ($postCategoryId && $postCategoryId == $category->getId()) {
               $result[] = $postRewrite;
           }
       }

       return $result;
   }
}
