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

namespace Aheadworks\Blog\Controller\Email\Notification\Settings;

use Aheadworks\Blog\Controller\AbstractFrontendAction;
use Aheadworks\Blog\Model\Config;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Aheadworks\Blog\Model\Url\Builder\Frontend as FrontendUrlBuilder;
use Aheadworks\Blog\Model\Email\Queue\Item\SecurityCode\Checker as SecurityCodeChecker;
use Magento\Framework\Controller\Result\Redirect;

class View extends AbstractFrontendAction
{
    /**
     * @param Context $context
     * @param Config $config
     * @param PageFactory $resultPageFactory
     * @param SecurityCodeChecker $securityCodeChecker
     */
    public function __construct(
        Context $context,
        Config $config,
        private readonly PageFactory $resultPageFactory,
        private readonly SecurityCodeChecker $securityCodeChecker
    ) {
        parent::__construct($context, $config);
    }

    /**
     * View notification settings
     *
     * @return Redirect|Page
     */
    public function execute()
    {
        $securityCode = $this->getRequest()->getParam(
            FrontendUrlBuilder::SECURITY_CODE_REQUEST_PARAMETER_NAME,
            ''
        );
        if ($this->securityCodeChecker->isValid($securityCode)) {
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Post Comments Notifications'));

            return $resultPage;
        }

        $this->messageManager->addErrorMessage(__('Unsubscribe link has already expired.'));

        return $this->getPreparedRedirect();
    }

    /**
     * Retrieve redirect to base page
     *
     * @return Redirect
     */
    protected function getPreparedRedirect(): Redirect
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectUrl = $this->_url->getBaseUrl();
        $resultRedirect->setUrl($redirectUrl);

        return $resultRedirect;
    }
}
