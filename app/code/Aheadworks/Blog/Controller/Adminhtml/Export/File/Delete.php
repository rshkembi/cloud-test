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
namespace Aheadworks\Blog\Controller\Adminhtml\Export\File;

use Aheadworks\Blog\Model\Directory;
use Magento\Backend\App\Action;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\ValidatorException;
use Magento\ImportExport\Controller\Adminhtml\Export as ExportController;
use Magento\Framework\Filesystem;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Filesystem\Directory\WriteFactory;

/**
 * Controller that delete file by name.
 */
class Delete extends ExportController implements HttpPostActionInterface
{
    /**
     * Url to this controller
     */
    const URL = 'aw_blog_admin/export_file/delete';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var WriteFactory
     */
    private $writeFactory;

    /**
     * Delete constructor.
     *
     * @param Action\Context $context
     * @param Filesystem $filesystem
     * @param WriteFactory $writeFactory
     */
    public function __construct(
        Action\Context $context,
        Filesystem $filesystem,
        WriteFactory $writeFactory
    ) {
        $this->filesystem = $filesystem;
        $this->writeFactory = $writeFactory;
        parent::__construct($context);
    }

    /**
     * Controller basic method implementation.
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath($this->_redirect->getRefererUrl());
        try {
            if (empty($fileName = $this->getRequest()->getParam('filename'))) {
                $this->messageManager->addErrorMessage(__('Please provide valid export file name'));

                return $resultRedirect;
            }
            $directoryWrite = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            try {
                $directoryWrite->delete($directoryWrite->getAbsolutePath() . Directory::AW_BLOG_EXPORT . '/' . $fileName);
                $this->messageManager->addSuccessMessage(__('File %1 deleted', $fileName));
            } catch (ValidatorException $exception) {
                $this->messageManager->addErrorMessage(
                    __('Sorry, but the data is invalid or the file is not uploaded.')
                );
            } catch (FileSystemException $exception) {
                $this->messageManager->addErrorMessage(
                    __('Sorry, but the data is invalid or the file is not uploaded.')
                );
            }
        } catch (FileSystemException $exception) {
            $this->messageManager->addErrorMessage(__('There are no export file with such name %1', $fileName));
        }

        return $resultRedirect;
    }
}