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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\BlogSearch\Model\Converter;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Model\Resolver\AuthorName as AuthorNameResolver;

/**
 * Class PostToEsDocument
 */
class PostToEsDocument
{
    /**
     * @var AuthorNameResolver
     */
    private $authorNameResolver;

    /**
     * PostToEsDocument constructor.
     * @param AuthorNameResolver $authorNameResolver
     */
    public function __construct(
        AuthorNameResolver $authorNameResolver
    ) {
        $this->authorNameResolver = $authorNameResolver;
    }

    /**
     * Post - es document map
     *
     * @param PostInterface $post
     * @returns array
     */
    public function convert($post)
    {
        $author = $post->getAuthor() ? $this->authorNameResolver->getFullAuthorName($post->getAuthor() ) : '';

        return [
            PostInterface::TITLE => $post->getTitle(),
            PostInterface::CONTENT => $post->getContent(),
            PostInterface::AUTHOR => $author,
            PostInterface::TAG_NAMES => $post->getTagNames(),
            PostInterface::META_TITLE => $post->getMetaTitle(),
            PostInterface::META_KEYWORDS => $post->getMetaKeywords(),
            PostInterface::META_DESCRIPTION => $post->getMetaDescription(),
        ];
    }
}
