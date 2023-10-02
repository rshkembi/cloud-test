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
namespace Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata;

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata\UrlConfigMetadataFactory;

/**
 * Class responsible for creating UrlConfigMetadata object for store
 *
 * Class StoreUrlConfigMetadataFactory
 * @package Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata
 */
class StoreUrlConfigMetadataFactory
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var UrlConfigMetadataFactory
     */
    private $urlConfigMetadataFactory;

    /**
     * StoreUrlConfigMetadataFactory constructor.
     * @param Config $config
     * @param UrlConfigMetadataFactory $urlConfigMetadataFactory
     */
    public function __construct(
        Config $config,
        UrlConfigMetadataFactory $urlConfigMetadataFactory
    ) {
        $this->config = $config;
        $this->urlConfigMetadataFactory = $urlConfigMetadataFactory;
    }

    /**
     * Creates UrlConfigMetadata object for store
     *
     * @param int $storeId
     * @return UrlConfigMetadata
     */
    public function create($storeId)
    {
        return $this->urlConfigMetadataFactory
            ->create()
            ->setRouteToBlog($this->config->getRouteToBlog($storeId))
            ->setRouteToAuthors($this->config->getRouteToAuthors($storeId))
            ->setSeoUrlType($this->config->getSeoUrlType($storeId))
            ->setPostUrlSuffix($this->config->getPostUrlSuffix($storeId))
            ->setAuthorUrlSuffix($this->config->getAuthorUrlSuffix($storeId))
            ->setUrlSuffixForOtherPages($this->config->getUrlSuffixForOtherPages($storeId))
            ->setStoreId($storeId);
    }
}
