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
namespace Aheadworks\Blog\Model\Import\Data\Processor\Post;

use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Post\Author\Resolver as AuthorResolver;
use Aheadworks\Blog\Api\Data\PostInterface;

/**
 * Class Author
 */
class Author implements ProcessorInterface
{
    /**
     * @var AuthorResolver
     */
    private $authorResolver;

    /**
     * Author constructor.
     * @param AuthorResolver $authorResolver
     */
    public function __construct(
        AuthorResolver $authorResolver
    ) {
        $this->authorResolver = $authorResolver;
    }

    /**
     * @inheritDoc
     */
    public function process($data)
    {
        if (isset($data[PostInterface::AUTHOR]) && $data[PostInterface::AUTHOR]) {
            try {
                $data[PostInterface::AUTHOR] = trim($data[PostInterface::AUTHOR]);
                $data[PostInterface::AUTHOR_ID] = $this->authorResolver->resolveId($data, PostInterface::AUTHOR);
            } catch (\Error $e) {
                $data[PostInterface::AUTHOR_ID] = null;
            }
        }

        return $data;
    }
}