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

namespace Aheadworks\Blog\Controller\Adminhtml\Comment\Disqus;

use Aheadworks\Blog\Model\DisqusCommentsService;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;

class Index extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::comments_disqus';

    /**
     * @param Context $context
     * @param DisqusCommentsService $disqusCommentsService
     */
    public function __construct(
        Context $context,
        private readonly DisqusCommentsService $disqusCommentsService
    ) {
        parent::__construct($context);
    }

    /**
     * Redirect page
     *
     * @return Redirect
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($this->disqusCommentsService->getModerateUrl());

        return $resultRedirect;
    }
}
