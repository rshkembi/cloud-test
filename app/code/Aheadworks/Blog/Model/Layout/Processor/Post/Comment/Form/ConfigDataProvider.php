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

namespace Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Form;

use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Layout\Processor\Post\Comment\Form\AgreementsConfig as CommentFormAgreementsConfig;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Api\Data\StoreInterface;
use Aheadworks\Blog\Model\BuiltinConfig;

class ConfigDataProvider implements LayoutProcessorInterface
{
    /**
     * @param ArrayManager $arrayManager
     * @param HttpContext $httpContext
     * @param BuiltinConfig $builtinConfig
     * @param CommentFormAgreementsConfig $commentFormAgreementsConfig
     */
    public function __construct(
        protected readonly ArrayManager $arrayManager,
        protected readonly HttpContext $httpContext,
        protected readonly BuiltinConfig $builtinConfig,
        protected readonly CommentFormAgreementsConfig $commentFormAgreementsConfig
    ) {
    }

    /**
     * Process js Layout of block
     *
     * @param array $jsLayout
     * @param array|null $relatedObjectList
     * @return array
     */
    public function process(array $jsLayout, ?array $relatedObjectList = []): array
    {
        $commentFormProviderPath = 'components/awBlogCommentConfigProvider';
        $jsLayout = $this->arrayManager->merge(
            $commentFormProviderPath,
            $jsLayout,
            [
                'data' => $this->getConfigData(
                    $relatedObjectList[self::POST_KEY] ?? null,
                    $relatedObjectList[self::STORE_KEY] ?? null
                )
            ]
        );

        return $jsLayout;
    }

    /**
     * Retrieve config data
     *
     * @param PostInterface|null $post
     * @param StoreInterface|null $store
     * @return array
     */
    protected function getConfigData($post, $store): array
    {
        $currentStoreId = (isset($store)) ? (int)$store->getId() : null;
        $isCustomerLoggedIn = $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
        return [
            'is_customer_logged_in' => $isCustomerLoggedIn,
            'can_visitor_define_comment_visibility' => true,
            'is_need_to_render_form' =>
                !$isCustomerLoggedIn ? $this->builtinConfig->isAllowGuestComments($currentStoreId) : $isCustomerLoggedIn,
            'agreements_config' => $this->commentFormAgreementsConfig->getConfigData(
                (int)$store->getId()
            )
        ];
    }
}
