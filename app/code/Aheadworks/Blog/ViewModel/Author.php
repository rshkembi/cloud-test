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
namespace Aheadworks\Blog\ViewModel;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Model\Image\Info as ImageInfo;
use Aheadworks\Blog\Model\Url;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Filter\Template as TemplateProcessor;

/**
 * Class Author
 */
class Author implements ArgumentInterface
{
    /**
     * @var Url
     */
    private $url;

    /**
     * @var ImageInfo
     */
    private $imageInfo;

    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

    /**
     * Author constructor.
     * @param Url $url
     * @param ImageInfo $imageInfo
     * @param TemplateProcessor $templateProcessor
     */
    public function __construct(
        Url $url,
        ImageInfo $imageInfo,
        TemplateProcessor $templateProcessor
    ) {
        $this->url = $url;
        $this->imageInfo = $imageInfo;
        $this->templateProcessor = $templateProcessor;
    }

    /**
     * Get author image url
     *
     * @param AuthorInterface $author
     * @return string
     */
    public function getAuthorImageUrl($author)
    {
        return $author ? $this->imageInfo->getMediaUrl($author->getImageFile()) : '';
    }

    /**
     * Retrieve author url
     *
     * @param AuthorInterface $author
     * @return string
     */
    public function getAuthorUrl($author)
    {
        return $author ? $this->url->getAuthorUrl($author) : '';
    }

    /**
     * Retrieve author short bio
     *
     * @param AuthorInterface $author
     * @return string
     */
    public function getAuthorShortBio($author)
    {
        $result = '';

        if ($author->getShortBio()) {
            try {
                $result = $this->templateProcessor->filter($author->getShortBio());
            } catch (\Exception $e) {
                $result = $author->getShortBio();
            }
        }

        return $result;
    }
}
