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

use Magento\Framework\DataObject;

/**
 * Responsible for storing config data that used in url generating
 *
 * Class UrlConfigMetadata
 *
 * @method string getRouteToBlog()
 * @method $this setRouteToBlog(string $route)
 * @method string getRouteToAuthors()
 * @method $this setRouteToAuthors(string $route)
 * @method string getSeoUrlType()
 * @method $this setSeoUrlType(string $type)
 * @method string getPostUrlSuffix()
 * @method $this setPostUrlSuffix(string $suffix)
 * @method string getAuthorUrlSuffix()
 * @method $this setAuthorUrlSuffix(string $suffix)
 * @method string getUrlSuffixForOtherPages()
 * @method $this setUrlSuffixForOtherPages(string $suffix)
 * @method int getStoreId()
 * @method $this setStoreId(int $storeId)
 *
 * @package Aheadworks\Blog\Model\UrlRewrites\UrlConfigMetadata
 */
class UrlConfigMetadata extends DataObject
{
}
