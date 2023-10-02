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

class BuiltinConfig
{
    /**
     *  Configuration path to comment automatic approving
     */
    public const XML_PATH_AUTOMATIC_APPROVING = 'aw_blog/comments/is_automatic_approving';

    /**
     *  Configuration path to allow guest comments
     */
    public const XML_PATH_ALLOW_GUEST_COMMENTS = 'aw_blog/comments/is_allow_guest_comments';

    /**
     *  Configuration path to enable terms and conditions
     */
    public const XML_PATH_ENABLE_TERMS_AND_CONDITIONS = 'aw_blog/comments/enable_terms_and_conditions';

    /**
     *  Configuration path to agreements display mode
     */
    public const XML_PATH_GENERAL_AGREEMENTS_DISPLAY_MODE = 'aw_blog/comments/agreements_display_mode';

    private const XML_PATH_GENERAL_IS_CUSTOMER_SUBSCRIBED_TO_NOTIFICATION_BY_DEFAULT
        = 'aw_blog/comments/allow_subscribe_to_notification_automatically';
    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    /**
     * Is automatic approving
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isAutomaticApproving(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_AUTOMATIC_APPROVING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Allow guest comments
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isAllowGuestComments(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ALLOW_GUEST_COMMENTS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Enable terms and conditions
     *
     * @param int|null $storeId
     * @return bool
     */
    public function enableTermsAndConditions(?int $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_ENABLE_TERMS_AND_CONDITIONS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get display mode of terms and conditions
     *
     * @param int|null $storeId
     * @return int
     */
    public function getAgreementsDisplayMode(?int $storeId = null): int
    {
        return (int)$this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_AGREEMENTS_DISPLAY_MODE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if recipient is subscribed to email notifications by default
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isRecipientSubscribedToNotificationByDefault(?int $websiteId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_GENERAL_IS_CUSTOMER_SUBSCRIBED_TO_NOTIFICATION_BY_DEFAULT,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
    }
}
