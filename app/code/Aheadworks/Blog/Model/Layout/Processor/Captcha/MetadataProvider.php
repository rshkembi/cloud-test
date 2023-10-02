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

namespace Aheadworks\Blog\Model\Layout\Processor\Captcha;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Aheadworks\Blog\Model\Captcha\Checker as CaptchaChecker;
use Aheadworks\Blog\Model\Captcha\ConfigInterface as CaptchaConfigInterface;
use Aheadworks\Blog\Model\Captcha\Config\Factory as CaptchaConfigFactory;

class MetadataProvider implements LayoutProcessorInterface
{
    /**
     * Name of the field with captcha id
     */
    const CAPTCHA_ID_FIELD_NAME = 'captcha_id';

    /**
     * @var CaptchaConfigInterface
     */
    protected $captchaConfig;

    /**
     * @param CaptchaConfigFactory $captchaConfigFactory
     * @param ArrayManager $arrayManager
     * @param HttpContext $httpContext
     * @param CaptchaChecker $captchaChecker
     * @param string $formPath
     * @param string $formProvider
     * @param string $displayArea
     * @param string $captchaId
     * @param string $entityInterfaceName
     */
    public function __construct(
        protected readonly CaptchaConfigFactory $captchaConfigFactory,
        protected readonly ArrayManager $arrayManager,
        protected readonly HttpContext $httpContext,
        protected readonly CaptchaChecker $captchaChecker,
        protected string $formPath = '',
        protected string $formProvider = '',
        protected string $displayArea = '',
        protected string $captchaId = '',
        protected string $entityInterfaceName = ''
    ) {
        $this->captchaConfig = $captchaConfigFactory->create();
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null  $relatedObjectList
     * @return array
     * @throws ConfigurationMismatchException
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $store = $relatedObjectList[self::STORE_KEY] ?? null;

        if (empty($this->formPath)
            || empty($this->formProvider)
            || empty($this->displayArea)
            || empty($this->captchaId)
            || empty($this->entityInterfaceName)
        ) {
            throw new ConfigurationMismatchException(
                __('Specify captcha component metadata settings')
            );
        }

        if ($this->isNeedToAddCaptchaComponent($store)) {
            $jsLayout = $this->arrayManager->merge(
                $this->formPath . '/children',
                $jsLayout,
                [
                    'captcha' => $this->getCaptchaComponentMetadata(),
                    self::CAPTCHA_ID_FIELD_NAME => $this->getCaptchaIdFieldMetadata(),
                ]
            );
        }

        return $jsLayout;
    }

    /**
     * Check if need to add captcha component
     *
     * @param StoreInterface|null $store
     * @return bool
     */
    private function isNeedToAddCaptchaComponent(?StoreInterface $store): bool
    {
        $websiteId = ($store) ? (int)$store->getWebsiteId() : null;

        return $this->captchaChecker->isNeedToApplyCaptcha(
            $websiteId,
            $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH),
            $this->entityInterfaceName
        );
    }

    /**
     * Retrieve captcha component metadata
     *
     * @return array
     */
    private function getCaptchaComponentMetadata(): array
    {
        return $this->captchaConfig->getCaptchaUiComponentMetadata(
            $this->captchaId,
            [
                'displayArea' => $this->displayArea
            ]
        );
    }

    /**
     * Retrieve metadata for hidden field with captcha id value
     *
     * @return array
     */
    private function getCaptchaIdFieldMetadata(): array
    {
        return [
            'displayArea' => $this->displayArea,
            'component' => 'Magento_Ui/js/form/element/abstract',
            'dataScope' => self::CAPTCHA_ID_FIELD_NAME,
            'provider' => $this->formProvider,
            'template' => 'ui/form/field',
            'visible' => false,
            'value' => $this->captchaId,
            'sortOrder' => 100,
        ];
    }
}
