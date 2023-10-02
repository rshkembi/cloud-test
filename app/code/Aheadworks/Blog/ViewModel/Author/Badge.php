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
namespace Aheadworks\Blog\ViewModel\Author;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\ViewModel\Author;
use Aheadworks\Blog\Model\Url;
use Aheadworks\Blog\Model\Image\Info as ImageInfo;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\Filter\Template as TemplateProcessor;

/**
 * Class Badge
 */
class Badge extends Author
{
    /**
     * Badge constructor.
     * @param Url $url
     * @param ImageInfo $imageInfo
     * @param TemplateProcessor $templateProcessor
     */
    public function __construct(
        Url $url,
        ImageInfo $imageInfo,
        TemplateProcessor $templateProcessor
    ) {
        parent::__construct($url, $imageInfo, $templateProcessor);
    }

    /**
     * Retrieve author full name
     *
     * @param AuthorInterface $author
     * @return string
     */
    public function getAuthorFullname($author)
    {
        return $author ? $author->getFirstname() . ' ' . $author->getLastname() : '';
    }
}
