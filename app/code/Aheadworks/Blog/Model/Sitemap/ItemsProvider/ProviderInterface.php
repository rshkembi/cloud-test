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
namespace Aheadworks\Blog\Model\Sitemap\ItemsProvider;

/**
 * Interface ProviderInterface
 * @package Aheadworks\Blog\Model\Sitemap\ItemsProvider
 */
interface ProviderInterface
{
    /**
     * Retrieve sitemap items
     *
     * @param int $storeId
     * @return array
     */
    public function getItems($storeId);

    /**
     * Retrieve sitemap items 2.3.x compatibility
     *
     * @param int $storeId
     * @return array
     */
    public function getItems23x($storeId);
}
