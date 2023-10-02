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
use Aheadworks\Blog\Model\Post\Author\Resolver as PostAuthorResolver;

/**
 * Class Author
 *
 * @package Aheadworks\Blog\Model\Post\StructuredData\Provider
 */
class Author implements ProviderInterface
{
    /**
     * @var PostAuthorResolver
     */
    private $postAuthorResolver;

    /**
     * @param PostAuthorResolver $postAuthorResolver
     */
    public function __construct(
        PostAuthorResolver $postAuthorResolver
    ) {
        $this->postAuthorResolver = $postAuthorResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getData($post)
    {
        $data = [];

        $authorFullName = $this->postAuthorResolver->getFullName($post);
        if (!empty($authorFullName)) {
            $data["author"] = [
                "@type" => "Person",
                "name" => $authorFullName,
            ];
        }

        return $data;
    }
}
