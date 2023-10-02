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
namespace Aheadworks\Blog\Model\Import\Data\Processor\Author;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Aheadworks\Blog\Model\Data\Processor\ProcessorInterface;
use Aheadworks\Blog\Model\Post\Author\Resolver as AuthorResolver;

/**
 * Class AuthorId
 */
class AuthorId implements ProcessorInterface
{
    const FULL_NAME = 'fullname';

    /**
     * @var AuthorResolver
     */
    private $authorResolver;

    /**
     * AuthorId constructor.
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
        try {
            $data[self::FULL_NAME] = $data[AuthorInterface::FIRSTNAME] . ' ' . $data[AuthorInterface::LASTNAME];
            $data[AuthorInterface::ID] = $this->authorResolver->resolveId($data, self::FULL_NAME);
        } catch (\Error $e) {
            $data[AuthorInterface::ID] = null;
        }

        return $data;
    }
}