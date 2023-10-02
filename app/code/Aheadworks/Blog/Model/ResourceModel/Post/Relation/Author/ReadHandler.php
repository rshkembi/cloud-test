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
namespace Aheadworks\Blog\Model\ResourceModel\Post\Relation\Author;

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Aheadworks\Blog\Api\AuthorRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class ReadHandler
 * @package Aheadworks\Blog\Model\ResourceModel\Post\Relation\Author
 */
class ReadHandler implements ExtensionInterface
{
    /**
     * @var AuthorRepositoryInterface
     */
    private $authorRepository;

    /**
     * @param AuthorRepositoryInterface $authorRepository
     */
    public function __construct(AuthorRepositoryInterface $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * {@inheritdoc}
     * @param PostInterface $entity
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entityId = (int)$entity->getId() && $entity->getAuthorId()) {
            try {
                $author = $this->authorRepository->get($entity->getAuthorId());
                $entity->setAuthor($author);
            } catch (LocalizedException $e) {
            }
        }
        return $entity;
    }
}
