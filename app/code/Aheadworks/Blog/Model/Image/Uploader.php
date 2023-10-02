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
namespace Aheadworks\Blog\Model\Image;

use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * Class Uploader
 * @package Aheadworks\Blog\Model\Image
 */
class Uploader
{
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var Info
     */
    private $imageInfo;

    /**
     * @param UploaderFactory $uploaderFactory
     * @param Info $imageInfo
     */
    public function __construct(
        UploaderFactory $uploaderFactory,
        Info $imageInfo
    ) {
        $this->uploaderFactory = $uploaderFactory;
        $this->imageInfo = $imageInfo;
    }

    /**
     * Upload image to media directory
     *
     * @param string $fileId
     * @return array
     * @throws \Exception
     */
    public function uploadToMediaFolder($fileId)
    {
        $result = ['file' => '', 'size' => '', 'type' => '', 'cssWidth' => '', 'cssHeight' => ''];
        $mediaDirectory = $this->imageInfo
            ->getMediaDirectory()
            ->getAbsolutePath(Info::FILE_DIR);

        /** @var \Magento\MediaStorage\Model\File\Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader
            ->setAllowRenameFiles(true)
            ->setFilesDispersion(false)
            ->setAllowedExtensions($this->getAllowedExtensions());

        $result = array_intersect_key($uploader->save($mediaDirectory), $result);
        $result = array_merge($this->imageInfo->getImgSizeForCss($result['file']), $result);
        $result['url'] = $this->imageInfo->getMediaUrl($result['file']);

        return $result;
    }

    /**
     * Get allowed file extensions
     *
     * @return string[]
     */
    public function getAllowedExtensions()
    {
        return ['jpg', 'jpeg', 'gif', 'png'];
    }
}
