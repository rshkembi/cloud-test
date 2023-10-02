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

namespace Aheadworks\Blog\Model\Captcha\Config;

use Aheadworks\Blog\Model\Captcha\ConfigInterface as CaptchaConfigInterface;
use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Blog\Model\ThirdPartyModule\Checker as ThirdPartyModuleChecker;
use Aheadworks\Blog\Model\Captcha\Config\Module\ReCaptchaUi as ReCaptchaUiConfig;
use Aheadworks\Blog\Model\Captcha\Config\Dummy as DummyConfig;

class Factory
{
    /**
     * @param ObjectManagerInterface $objectManager
     * @param ThirdPartyModuleChecker $thirdPartyModuleChecker
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ThirdPartyModuleChecker $thirdPartyModuleChecker
    ) {
    }

    /**
     * Create captcha config according to the used module
     *
     * @return CaptchaConfigInterface
     */
    public function create()
    {
        if ($this->thirdPartyModuleChecker->isMagentoRecaptchaUiModuleEnabled()) {
            return $this->objectManager->create(ReCaptchaUiConfig::class);
        }

        return $this->objectManager->create(DummyConfig::class);
    }
}
