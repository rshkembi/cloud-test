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

use Aheadworks\Blog\Model\Captcha\Resolver as CaptchaResolver;
use Aheadworks\Blog\Model\Captcha\ConfigInterface as CaptchaConfigInterface;
use Aheadworks\Blog\Model\Captcha\Config\Factory as CaptchaConfigFactory;

class Checker
{
    /**
     * @var CaptchaConfigInterface
     */
    private $captchaConfig;

    /**
     * @param CaptchaConfigFactory $captchaConfigFactory
     * @param Resolver $captchaResolver
     */
    public function __construct(
        private readonly CaptchaConfigFactory $captchaConfigFactory,
        private readonly CaptchaResolver $captchaResolver
    ) {
        $this->captchaConfig = $captchaConfigFactory->create();
    }

    /**
     * Check if need to apply captcha on entity for specific visitor type
     *
     * @param int|null $websiteId
     * @param bool $isCustomerLoggenIn
     * @param string $entityInterfaceName
     * @return bool
     */
    public function isNeedToApplyCaptcha(?int $websiteId, bool $isCustomerLoggenIn, string $entityInterfaceName)
    {
        $reCAPTCHADisplayModeList = $this->captchaConfig->getCaptchaDisplayModeList($websiteId);

        $allowedDisplayModeListByEntity = $this->captchaResolver->getAllowedDisplayModeListByEntity(
            $entityInterfaceName
        );
        $allowedDisplayModeListByCustomer = $this->captchaResolver->getAllowedDisplayModeListByCustomer(
            $isCustomerLoggenIn
        );
        $displayModeIntersection = array_intersect(
            $allowedDisplayModeListByEntity,
            $allowedDisplayModeListByCustomer,
            $reCAPTCHADisplayModeList
        );
        return (
            $this->captchaConfig->isCaptchaEnabled()
            && count($displayModeIntersection) > 0
        );
    }
}
