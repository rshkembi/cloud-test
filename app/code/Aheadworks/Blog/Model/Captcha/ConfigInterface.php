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

namespace Aheadworks\Blog\Model\Captcha;

interface ConfigInterface
{
    /**
     * Check if Captcha is enabled
     *
     * @param int|null $websiteId
     * @return bool
     */
    public function isCaptchaEnabled(?int $websiteId = null): bool;

    /**
     * Get list of enabled Captcha display mode
     *
     * @param int|null $websiteId
     * @return array
     */
    public function getCaptchaDisplayModeList(?int $websiteId = null): array;

    /**
     * Retrieve UI component metadata for captcha rendering
     *
     * @param string $captchaId
     * @param array $additionalMetadata
     * @return array
     */
    public function getCaptchaUiComponentMetadata(string $captchaId, array $additionalMetadata = []): array;
}
