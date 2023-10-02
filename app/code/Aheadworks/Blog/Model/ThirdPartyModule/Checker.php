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

namespace Aheadworks\Blog\Model\ThirdPartyModule;

use Magento\Framework\Module\ModuleListInterface;

class Checker
{
    /**#@+
     * Constants for third party module names
     */
    const MAGENTO_RECAPTCHA_UI_MODULE_NAME = 'Magento_ReCaptchaUi';
    /**#@-*/

    /**
     * @param ModuleListInterface $moduleList
     */
    public function __construct(
        private readonly ModuleListInterface $moduleList
    ) {
    }

    /**
     * Check if Magento 2.4.X ReCaptcha module family is enabled
     *
     * @return bool
     */
    public function isMagentoRecaptchaUiModuleEnabled(): bool
    {
        return $this->moduleList->has(self::MAGENTO_RECAPTCHA_UI_MODULE_NAME);
    }
}
