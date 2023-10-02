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
use Aheadworks\Blog\Model\Url as BlogUrl;

/**
 * Class MainEntityOfPage
 *
 * @package Aheadworks\Blog\Model\Post\StructuredData\Provider
 */
class MainEntityOfPage implements ProviderInterface
{
    /**
     * @var BlogUrl
     */
    private $blogUrl;

    /**
     * @param BlogUrl $blogUrl
     */
    public function __construct(
        BlogUrl $blogUrl
    ) {
        $this->blogUrl = $blogUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($post)
    {
        return [
            "mainEntityOfPage" => [
                "@type" => "WebPage",
                "@id" => $this->blogUrl->getCanonicalUrl($post),
            ]
        ];
    }
}
