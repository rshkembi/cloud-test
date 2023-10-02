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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\Captcha;

use Aheadworks\Blog\Model\Captcha\Checker as CaptchaChecker;
use Aheadworks\Blog\Model\Layout\Processor\Captcha\MetadataProvider as CaptchaMetadataProvider;
use Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Reply\Form\ElementNameUpdater\ElementNameResolver;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Stdlib\ArrayManager;
use Aheadworks\Blog\Model\Captcha\Config\Factory as CaptchaConfigFactory;

class MetadataProvider extends CaptchaMetadataProvider
{
    /**
     * @param CaptchaConfigFactory $captchaConfigFactory
     * @param ArrayManager $arrayManager
     * @param HttpContext $httpContext
     * @param CaptchaChecker $captchaChecker
     * @param ElementNameResolver $elementNameResolver
     * @param string $formPath
     * @param string $formProvider
     * @param string $displayArea
     * @param string $captchaId
     * @param string $entityInterfaceName
     */
    public function __construct(
        CaptchaConfigFactory $captchaConfigFactory,
        ArrayManager $arrayManager,
        HttpContext $httpContext,
        CaptchaChecker $captchaChecker,
        private readonly ElementNameResolver $elementNameResolver,
        string $formPath = '',
        string $formProvider = '',
        string $displayArea = '',
        string $captchaId = '',
        string $entityInterfaceName = ''
    ) {
        parent::__construct(
            $captchaConfigFactory,
            $arrayManager,
            $httpContext,
            $captchaChecker,
            $formPath,
            $formProvider,
            $displayArea,
            $captchaId,
            $entityInterfaceName
        );
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     * @throws ConfigurationMismatchException
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $commentId = $relatedObjectList[self::COMMENT_ID_KEY] ?? null;

        $originalFormPath = $this->formPath;
        $originalFormProvider = $this->formProvider;
        $originalCaptchaId = $this->captchaId;

        $this->formPath .= $this->elementNameResolver->getElementNamePostfix($commentId);
        $this->formProvider .= $this->elementNameResolver->getElementNamePostfix($commentId);
        $this->captchaId .= $this->elementNameResolver->getElementNamePostfix($commentId);

        $jsLayout = parent::process($jsLayout, $relatedObjectList);

        $this->formPath = $originalFormPath;
        $this->formProvider = $originalFormProvider;
        $this->captchaId = $originalCaptchaId;

        return $jsLayout;
    }
}
