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

namespace Aheadworks\Blog\Model\Captcha\Config\Module\MagentoCaptcha;

use Magento\Captcha\Model\Checkout\ConfigProvider as MagentoCaptchaCheckoutConfigProvider;

class ConfigProvider extends MagentoCaptchaCheckoutConfigProvider
{
    /**
     * Retrieve config data for Magento captcha
     *
     * @param string $formId
     * @return array
     */
    public function getConfigData(string $formId): array
    {
        $this->formIds = [$formId];
        $captchaCheckoutConfigData = $this->getConfig();
        return $captchaCheckoutConfigData['captcha'][$formId] ?? [];
    }

    /**
     * Whether captcha is required to be inserted to this form
     *
     * @param string $formId
     * @return bool
     */
    protected function isRequired($formId)
    {
        return true;
    }
}
