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

namespace Aheadworks\Blog\Ui\Component\Post\Form\Element;

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Config\Comments\Service;
use Aheadworks\Blog\Model\Url\Builder\Comment\UrlBuilder;
use Magento\Backend\Model\Auth\Session as AuthSession;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class CommentsLink extends Input
{
    /**
     * @param ContextInterface $context
     * @param AuthSession $authSession
     * @param UrlBuilder $commentUrlBuilder
     * @param Config $config
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        private readonly AuthSession $authSession,
        private readonly UrlBuilder $commentUrlBuilder,
        private readonly Config $config,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
    }

    /**
     * Prepare component configuration
     *
     * @return void
     */
    public function prepare()
    {
        $config = $this->getData('config');
        $commentAcl = $this->config->getCommentType() === Service::DISQUS
            ? 'Aheadworks_Blog::comments_disqus' : 'Aheadworks_Blog::comments_builtin';
        if (!isset($config['url'])
            && ($this->authSession->isAllowed($commentAcl))
        ) {
            $config['url'] = $this->commentUrlBuilder->getCommentUrl();
            $config['linkLabel'] = __('Go To Comments');
            $this->setData('config', $config);
        }
        parent::prepare();
    }
}
