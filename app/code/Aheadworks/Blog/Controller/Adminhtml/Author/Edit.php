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
namespace Aheadworks\Blog\Controller\Adminhtml\Author;

use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Controller\Result\Redirect;

/**
 * Class Edit
 * @package Aheadworks\Blog\Controller\Adminhtml\Author
 */
class Edit extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::authors';

    /**
     * @var PageFactory
     */
    private $resultPageFactory;

    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        AuthorRepositoryInterface $authorRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->authorRepository = $authorRepository;
    }

    /**
     * Author edit action
     *
     * @return Page|Redirect
     */
    public function execute()
    {
        $authorId = (int)$this->getRequest()->getParam('id');
        if ($authorId) {
            try {
                $this->authorRepository->get($authorId);
            } catch (LocalizedException $exception) {
                $this->messageManager->addException($exception, __('Something went wrong while editing the author.'));
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/');
                return $resultRedirect;
            }
        }
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage
            ->setActiveMenu('Aheadworks_Blog::authors')
            ->getConfig()->getTitle()->prepend(
                $authorId ?  __('Edit Author') : __('New Author')
            );
        return $resultPage;
    }
}
