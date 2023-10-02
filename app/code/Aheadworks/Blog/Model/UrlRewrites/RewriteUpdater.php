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
namespace Aheadworks\Blog\Model\UrlRewrites;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use Magento\UrlRewrite\Model\StorageInterface as RewriteStorageInterface;

/**
 * Class responsible for updating url rewrites
 *
 * Class RewriteUpdater
 * @package Aheadworks\Blog\Model\UrlRewrites
 */
class RewriteUpdater
{
    /**
     * @var RewriteStorageInterface
     */
    private $rewriteStorage;

    /**
     * RewriteUpdater constructor.
     * @param RewriteStorageInterface $rewriteStorageInterface
     */
    public function __construct(
        RewriteStorageInterface $rewriteStorage
    ) {
        $this->rewriteStorage = $rewriteStorage;
    }

    /**
     * @param UrlRewrite[] $urls
     */
    public function update(array $urls)
    {
        $this->rewriteStorage->replace($urls);
    }
}
