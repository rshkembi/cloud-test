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

namespace Aheadworks\Blog\Model\Captcha\Config\Module;

use Aheadworks\Blog\Model\Captcha\ConfigInterface as CaptchaConfigInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\InputException;
use Magento\Framework\ObjectManagerInterface;
use Magento\ReCaptchaUi\Model\IsCaptchaEnabledInterface;
use Magento\ReCaptchaUi\Model\UiConfigResolverInterface;
use Magento\Store\Model\ScopeInterface;
use Aheadworks\Blog\Model\System\Config\Value\Parser as ConfigValueParser;

class ReCaptchaUi implements CaptchaConfigInterface
{
    /**#@+
     * Constants for path to config values
     */
    const AW_BLOG_COMMENT_IS_CAPTCHA_ENABLED_KEY_VALUE = 'aw_blog_comment';
    const XML_PATH_RECAPTCHA_DISPLAY_MODE_LIST
        = 'recaptcha_frontend/type_for/aw_blog_comment_recaptcha_display_mode';
    /**#@-*/

    /**
     * @param ObjectManagerInterface $objectManager
     * @param ScopeConfigInterface $scopeConfig
     * @param ConfigValueParser $configValueParser
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly ConfigValueParser $configValueParser
    ) {
    }

    /**
     * Check if Captcha is enabled
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isCaptchaEnabled(?int $websiteId = null): bool
    {
        try {
            /** @var IsCaptchaEnabledInterface $isCaptchaEnabled */
            $isCaptchaEnabled = $this->objectManager->create(
                IsCaptchaEnabledInterface::class
            );
            return $isCaptchaEnabled->isCaptchaEnabledFor(
                self::AW_BLOG_COMMENT_IS_CAPTCHA_ENABLED_KEY_VALUE
            );
        } catch (InputException $exception) {
            return false;
        }
    }

    /**
     * Get list of enabled Captcha display mode
     *
     * @param int|null $websiteId
     * @return array
     */
    public function getCaptchaDisplayModeList(?int $websiteId = null): array
    {
        $multiselectConfigValue = $this->scopeConfig->getValue(
            self::XML_PATH_RECAPTCHA_DISPLAY_MODE_LIST,
            ScopeInterface::SCOPE_WEBSITE,
            $websiteId
        );
        return $this->configValueParser->getParsedMultiselectConfigValue($multiselectConfigValue);
    }

    /**
     * Retrieve UI component metadata for captcha rendering
     *
     * @param string $captchaId
     * @param array $additionalMetadata
     * @return array
     */
    public function getCaptchaUiComponentMetadata(string $captchaId, array $additionalMetadata = []): array
    {
        $basicMetadata = [
            'component' => 'Magento_ReCaptchaFrontendUi/js/reCaptcha',
            'settings' => $this->getCaptchaSettingsData(),
            'reCaptchaId' => $captchaId,
        ];

        return array_merge_recursive([], $basicMetadata, $additionalMetadata);
    }

    /**
     * Retrieve settings data for captcha UI component
     *
     * @return array
     */
    private function getCaptchaSettingsData()
    {
        try {
            /** @var UiConfigResolverInterface $captchaUiConfigResolver */
            $captchaUiConfigResolver = $this->objectManager->create(
                UiConfigResolverInterface::class
            );
            return $captchaUiConfigResolver->get(
                self::AW_BLOG_COMMENT_IS_CAPTCHA_ENABLED_KEY_VALUE
            );
        } catch (InputException $exception) {
            return [];
        }
    }
}
