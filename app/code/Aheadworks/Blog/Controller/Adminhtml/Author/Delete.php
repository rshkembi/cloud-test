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
use Magento\Backend\App\Action;

/**
 * Class Delete
 * @package Aheadworks\Blog\Controller\Adminhtml\Author
 */
class Delete extends Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Aheadworks_Blog::authors';

    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @param Context $context
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(
        Context $context,
        AuthorRepositoryInterface $authorRepository
    ) {
        parent::__construct($context);
        $this->authorRepository = $authorRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $authorId = (int)$this->getRequest()->getParam('id');
        if ($authorId) {
            try {
                $this->authorRepository->deleteById($authorId);
                $this->messageManager->addSuccessMessage(__('Author was successfully deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
            }
        }
        $this->messageManager->addErrorMessage(__('Author could not be deleted.'));
        return $resultRedirect->setPath('*/*/');
    }
}
