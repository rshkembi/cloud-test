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
declare(strict_types=1);

namespace Aheadworks\Blog\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class DisqusConfig
{
    /**
     * Configuration path to Disqus forum code
     */
    const XML_PATH_DISQUS_FORUM_CODE = 'aw_blog/comments/disqus_forum_code';

    /**
     * Configuration path to Disqus secret API key
     */
    const XML_PATH_DISQUS_SECRET_KEY = 'aw_blog/comments/disqus_secret_key';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    /**
     * Get forum code
     *
     * @param int|null $websiteId
     * @return string
     */
    public function getForumCode($websiteId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DISQUS_FORUM_CODE,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }

    /**
     * Get secret API key
     *
     * @param int $websiteId
     * @return string
     */
    public function getSecretKey($websiteId)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_DISQUS_SECRET_KEY,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
