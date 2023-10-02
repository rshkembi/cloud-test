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
namespace Aheadworks\Blog\Ui\DataProvider\Modifier\Category;

use Aheadworks\Blog\Api\Data\CategoryInterface;
use Aheadworks\Blog\Model\Image\Info as ImageInfo;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;

/**
 * Class ImageData
 *
 * @package Aheadworks\Blog\Ui\DataProvider\Modifier\Category
 */
class ImageData implements ModifierInterface
{
    /**
     * @var ImageInfo
     */
    private $imageInfo;

    /**
     * @param ImageInfo $imageInfo
     */
    public function __construct(
        ImageInfo $imageInfo
    ) {
        $this->imageInfo = $imageInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        if (!empty($data[CategoryInterface::IMAGE_FILE_NAME])) {
            $fileName = $data[CategoryInterface::IMAGE_FILE_NAME];
            try {
                $imageData = [
                    0 => [
                        'file' => $fileName,
                        'url' => $this->imageInfo->getMediaUrl($fileName),
                        'size' => $this->imageInfo->getStat($fileName)['size'],
                        'type' => 'image'
                    ]
                ];
            } catch (\Exception $exception) {
                $imageData = [];
            }
            $data[CategoryInterface::IMAGE_FILE_NAME] = $imageData;
        }
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
