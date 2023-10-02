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
namespace Aheadworks\Blog\Model\Seo;

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\StoreProvider;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\View\Page\Config as PageConfig;

/**
 * Class CanonicalIncluder
 */
class CanonicalIncluder
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Url
     */
    private $url;

    /**
     * @var StoreProvider
     */
    private $storeProvider;

    /**
     * CanonicalIncluder constructor.
     * @param Config $config
     * @param Url $url
     * @param StoreProvider $storeProvider
     */
    public function __construct(
        Config $config,
        Url $url,
        StoreProvider $storeProvider
    ) {
        $this->config = $config;
        $this->url = $url;
        $this->storeProvider = $storeProvider;
    }

    /**
     * Depending on the configuration add a canonical tag to the main blog page
     *
     * @param PageConfig $pageConfig
     * @return $this
     */
    public function includeOnBlogPage($pageConfig)
    {
        $currentStoreId = $this->storeProvider->getCurrentStoreId();

        if ($this->config->isUseBlogPageCanonicalTag($currentStoreId)) {
            $pageConfig->addRemotePageAsset(
                $this->url->getBlogHomeUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        }

        return $this;
    }
}