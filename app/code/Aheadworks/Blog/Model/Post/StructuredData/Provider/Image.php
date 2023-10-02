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
namespace Aheadworks\Blog\Model\Post\StructuredData\Provider;

use Aheadworks\Blog\Model\Post\StructuredData\ProviderInterface;
use Aheadworks\Blog\Model\Post\FeaturedImageInfo;

/**
 * Class Image
 *
 * @package Aheadworks\Blog\Model\Post\StructuredData\Provider
 */
class Image implements ProviderInterface
{
    /**
     * @var FeaturedImageInfo
     */
    protected $imageInfo;

    /**
     * @param FeaturedImageInfo $imageInfo
     */
    public function __construct(
        FeaturedImageInfo $imageInfo
    ) {
        $this->imageInfo = $imageInfo;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($post)
    {
        $data = [];

        $imageFile = $post->getFeaturedImageFile();
        $imageUrl = $this->imageInfo->getImageUrl($imageFile);
        if (!empty($imageFile) && !empty($imageUrl)) {
            $data["image"] = [
                "@type" => "ImageObject",
                "url" => $imageUrl,
            ];
        }

        return $data;
    }
}
