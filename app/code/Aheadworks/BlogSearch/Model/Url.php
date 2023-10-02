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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogSearch\Model;

use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Url
 */
class Url
{
    const SEARCH_CONTROLLER_PATH = 'aw_blog_search/search/index';
    const SEARCH_QUERY_PARAM = 'search';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var RewriteFriendlyUrlFinder
     */
    private $rewriteFriendlyUrlFinder;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * Url constructor.
     * @param StoreManagerInterface $storeManager
     * @param RewriteFriendlyUrlFinder $rewriteFriendlyUrlFinder
     * @param UrlInterface $urlBuilder
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        RewriteFriendlyUrlFinder $rewriteFriendlyUrlFinder,
        UrlInterface $urlBuilder
    ) {
        $this->storeManager = $storeManager;
        $this->rewriteFriendlyUrlFinder = $rewriteFriendlyUrlFinder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * Returns base url
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * Returns blog search url
     *
     * @param int $storeId
     * @return string
     */
    public function getBlogSearchUrl($storeId)
    {
        $friendlyUrl = $this->rewriteFriendlyUrlFinder->getFriendlyUrl(self::SEARCH_CONTROLLER_PATH, $storeId);

        return $friendlyUrl
            ? $this->urlBuilder->getDirectUrl($friendlyUrl)
            : $this->urlBuilder->getRouteUrl(self::SEARCH_CONTROLLER_PATH, []);
    }
}
