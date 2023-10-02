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

namespace Aheadworks\Blog\Block\Post\Comment\Reply\Form;

use Aheadworks\Blog\Block\Base;
use Aheadworks\Blog\Block\Post\Comment\Reply\Form as  ReplyFormBlock;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;
use Aheadworks\Blog\Model\Layout\LayoutProcessorProvider;
use Aheadworks\Blog\Model\Post\ResolverInterface as PostResolverInterface;
use Aheadworks\Blog\Model\StoreResolver;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\View\Element\Template\Context;

class VisibilityButton extends Base
{
    /**
     * @param Context $context
     * @param LayoutProcessorProvider $layoutProcessorProvider
     * @param Config $config
     * @param HttpContext $httpContext
     * @param StoreResolver $storeResolver
     * @param PostResolverInterface $postResolver
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayoutProcessorProvider $layoutProcessorProvider,
        Config $config,
        HttpContext $httpContext,
        private readonly StoreResolver $storeResolver,
        private readonly PostResolverInterface $postResolver,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $layoutProcessorProvider,
            $config,
            $httpContext,
            $data
        );
    }

    /**
     * Prepare layout
     *
     * @return VisibilityButton
     */
    protected function _prepareLayout()
    {
        $this->setData($this->getJsLayoutConfig());

        return parent::_prepareLayout();
    }

    /**
     * Get data role attribute value
     *
     * @return string
     */
    public function getDataRoleAttributeValue(): string
    {
        return parent::getDataRoleAttributeValue() . '-' . $this->getCommentId();
    }

    /**
     * Get data bind scope value
     *
     * @return string
     */
    public function getDataBindScopeValue(): string
    {
        return parent::getDataBindScopeValue() . '-' . $this->getCommentId();
    }

    /**
     * Retrieve js layout template
     *
     * @return array
     */
    public function getJsLayoutTemplate(): array
    {
        return (array)$this->getData('jsLayoutTemplate');
    }

    /**
     * Get related object list
     *
     * @return array
     */
    protected function getRelatedObjectList(): array
    {
        return [
            LayoutProcessorInterface::COMMENT_ID_KEY => $this->getCommentId(),
            LayoutProcessorInterface::JS_LAYOUT_TEMPLATE_KEY => $this->getJsLayoutTemplate(),
            LayoutProcessorInterface::STORE_KEY => $this->storeResolver->getCurrentStore(),
            LayoutProcessorInterface::POST_KEY => $this->postResolver->getCurrentPost(),
        ];
    }

    /**
     * Retrieve comment id from the parent block
     *
     * @return int|null
     */
    private function getCommentId(): ?int
    {
        $commentId = null;
        $parentBlock = $this->getParentBlock();
        if ($parentBlock
            && $parentBlock instanceof ReplyFormBlock
        ) {
            $commentId = $parentBlock->getCommentId();
        }

        return $commentId;
    }

    /**
     * Get js layout config
     *
     * @return array
     */
    public function getJsLayoutConfig(): array
    {
        return [
            'data_role_attribute_value' => 'aw-blog-comment-reply-form-visibility-button',
            'class_attribute_value' => 'aw-blog__comment-reply-form-visibility-button',
            'data_bind_scope_value' => 'awBlogCommentReplyFormVisibilityButton',
            'jsLayoutTemplate' => [
                'components' => [
                    'awBlogCommentReplyFormVisibilityButton' => [
                        'component' => 'Aheadworks_Blog/js/post/comment/reply/form/visibility-button',
                        'buttonLabel' => __('Reply'),
                        'formUiComponentName' => 'awBlogCommentReplyForm',
                        'formUiElementNameToFocus' => 'author_name',
                        'visible' => false,
                        'deps' => [
                            'awBlogCommentReplyForm'
                        ]
                    ]
                ]
            ]
        ];
    }
}
