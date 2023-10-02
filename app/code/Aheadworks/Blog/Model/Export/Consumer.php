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

namespace Aheadworks\Blog\Model\Export;

use Aheadworks\Blog\Model\Directory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Notification\NotifierInterface;
use Magento\ImportExport\Api\Data\ExportInfoInterface;
use Magento\ImportExport\Api\ExportManagementInterface;
use Psr\Log\LoggerInterface;

class Consumer
{
    /**
     * @param LoggerInterface $logger
     * @param ExportManagementInterface $exportManager
     * @param Filesystem $filesystem
     * @param NotifierInterface $notifier
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ExportManagementInterface $exportManager,
        private readonly Filesystem $filesystem,
        private readonly NotifierInterface $notifier
    ) {
    }

    /**
     * Process export
     *
     * @param ExportInfoInterface $exportInfo
     * @return void
     */
    public function process(ExportInfoInterface $exportInfo): void
    {
        try {
            $data = $this->exportManager->export($exportInfo);
            $fileName = $exportInfo->getFileName();
            $directory = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $directory->writeFile(Directory::AW_BLOG_EXPORT . '/' . $fileName, $data);

            $this->notifier->addMajor(
                __('Your export file is ready'),
                __('You can pick up your file at export main page')
            );
        } catch (FileSystemException $exception) {
            $this->notifier->addCritical(
                __('Error during export process occurred'),
                __('Error during export process occurred. Please check logs for detail')
            );
            $this->logger->critical('Something went wrong while export process. ' . $exception->getMessage());
        }
    }
}
