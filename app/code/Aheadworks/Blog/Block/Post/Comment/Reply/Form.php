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

namespace Aheadworks\Blog\Block\Post\Comment\Reply;

use Aheadworks\Blog\Block\Base as BaseBlock;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Layout\LayoutProcessorProvider;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template\Context;
use Aheadworks\Blog\Model\Post\ResolverInterface as PostResolverInterface;
use Aheadworks\Blog\Model\StoreResolver;
use Aheadworks\Blog\Model\Layout\LayoutProcessorInterface;

class Form extends BaseBlock
{
    /**
     * @param Context $context
     * @param LayoutProcessorProvider $layoutProcessorProvider
     * @param Config $config
     * @param HttpContext $httpContext
     * @param PostResolverInterface $postResolver
     * @param StoreResolver $storeResolver
     * @param UrlInterface $urlBuilder
     * @param array $data
     */
    public function __construct(
        Context $context,
        LayoutProcessorProvider $layoutProcessorProvider,
        Config $config,
        HttpContext $httpContext,
        private readonly PostResolverInterface $postResolver,
        private readonly StoreResolver $storeResolver,
        private readonly UrlInterface $urlBuilder,
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
     * @return Form
     */
    protected function _prepareLayout()
    {
        $this->setData('jsLayoutTemplate', $this->getJsLayoutConfig());

        return parent::_prepareLayout();
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
     * Retrieve current comment id
     *
     * @return int
     */
    public function getCommentId(): int
    {
        return $this->getData('comment_id');
    }

    /**
     * Set current comment id
     *
     * @param int|null $commentId
     * @return $this
     */
    public function setCommentId(?int $commentId): self
    {
        return $this->setData('comment_id', $commentId);
    }

    /**
     * Retrieve list of related objects
     *
     * @return array
     */
    protected function getRelatedObjectList(): array
    {
        return [
            LayoutProcessorInterface::POST_KEY => $this->postResolver->getCurrentPost(),
            LayoutProcessorInterface::STORE_KEY => $this->storeResolver->getCurrentStore(),
            LayoutProcessorInterface::COMMENT_ID_KEY => $this->getCommentId(),
            LayoutProcessorInterface::JS_LAYOUT_TEMPLATE_KEY => $this->getJsLayoutTemplate(),
        ];
    }

    /**
     * Get js layout config
     *
     * @return array
     */
    public function getJsLayoutConfig(): array
    {
        return [
            'components' => [
                'awBlogCommentReplyForm' => [
                    'sortOrder' => 10,
                    'component' => 'Aheadworks_Blog/js/post/comment/reply/form',
                    'buttonLabel' => 'Reply',
                    'formId' => 'comment-reply-form',
                    'formCss' => 'aw-blog-comment-reply-form',
                    'isVisible' => false,
                    'deps' => [
                        'awBlogCommentReplyFormProvider',
                        'awBlogCommentReplyConfigProvider'
                    ],
                    'dataScope' => 'data',
                    'provider' => 'awBlogCommentReplyFormProvider',
                    'configProvider' => 'awBlogCommentReplyConfigProvider',
                    'namespace' => 'aw_blog_post_comment_reply_form',
                    'additionalDataFormPartSelectorList' => [
                        '[name=g-recaptcha-response]',
                        '[name=captcha_string]'
                    ],
                    'children' => [
                        'author_name' => [
                            'displayArea' => 'fieldset',
                            'component' => 'Aheadworks_Blog/js/ui/form/element/input/visitor-name',
                            'dataScope' => 'author_name',
                            'provider' => 'awBlogCommentReplyFormProvider',
                            'template' => 'ui/form/field',
                            'label' => __('Name'),
                            'validation' => [
                                'required-entry' => true
                            ],
                            'sortOrder' => 10,
                        ],
                        'author_email' => [
                            'displayArea' => 'fieldset',
                            'component' => 'Magento_Ui/js/form/element/abstract',
                            'dataScope' => 'author_email',
                            'provider' => 'awBlogCommentReplyFormProvider',
                            'configProvider' => 'awBlogCommentReplyConfigProvider',
                            'template' => 'ui/form/field',
                            'elementTmpl' => 'ui/form/element/email',
                            'label' => __('Email'),
                            'validation' => [
                                'validate-email' => true,
                                'required-entry' => true
                            ],
                            'imports' => [
                                'visible' => '!${ $.configProvider }:data.is_customer_logged_in'
                            ],
                            'sortOrder' => 15,
                        ],
                        'content' => [
                            'displayArea' => 'fieldset',
                            'component' => 'Magento_Ui/js/form/element/textarea',
                            'dataScope' => 'content',
                            'provider' => 'awBlogCommentReplyFormProvider',
                            'template' => 'ui/form/field',
                            'label' => __('Text comment here'),
                            'validation' => [
                                'required-entry' => true
                            ],
                            'sortOrder' => 20,
                        ],
                        'post_id' => [
                            'displayArea' => 'fieldset',
                            'component' => 'Magento_Ui/js/form/element/abstract',
                            'dataScope' => 'post_id',
                            'provider' => 'awBlogCommentReplyFormProvider',
                            'template' => 'ui/form/field',
                            'visible' => false,
                            'sortOrder' => 30
                        ],
                        'comment_id' => [
                            'displayArea' => 'fieldset',
                            'component' => 'Magento_Ui/js/form/element/abstract',
                            'dataScope' => 'comment_id',
                            'provider' => 'awBlogCommentReplyFormProvider',
                            'template' => 'ui/form/field',
                            'visible' => false,
                            'sortOrder' => 40
                        ],
                    ],

                ],
                'awBlogCommentReplyFormProvider' => [
                    'component' => 'Magento_Ui/js/form/provider',
                    'config' => [
                        'submit_url' => $this->urlBuilder->getUrl('aw_blog/post_comment/submit')
                    ]
                ],
                'awBlogCommentReplyConfigProvider' => [
                    'component' => 'uiComponent'
                ]
            ]
        ];
    }
}
