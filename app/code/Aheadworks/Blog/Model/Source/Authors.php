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
namespace Aheadworks\Blog\Model\Source;

use Aheadworks\Blog\Api\Data\AuthorInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\Data\OptionSourceInterface;
use Aheadworks\Blog\Model\ResourceModel\Author\CollectionFactory;
use Aheadworks\Blog\Model\ResourceModel\Author\Collection;

/**
 * Class Authors
 * @package Aheadworks\Blog\Model\Source
 */
class Authors extends AbstractSource implements OptionSourceInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var array
     */
    private $options;

    /**
     * @param CollectionFactory $collectionFactoryFactory
     */
    public function __construct(CollectionFactory $collectionFactoryFactory)
    {
        $this->collectionFactory = $collectionFactoryFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $options = [];
            /** @var Collection $collection */
            $collection = $this->collectionFactory->create();
            $collection->addOrder(AuthorInterface::FIRSTNAME, 'ASC');
            /** @var AuthorInterface $author */
            foreach ($collection as $author) {
                $options[] = [
                    'label' => $author->getFirstname() . ' ' . $author->getLastname(),
                    'value' => $author->getId()
                ];
            }
            $this->options = $options;
        }

        return $this->options;
    }

    /**
     * @return array
     */
    public function getAvailableOptions()
    {
        $options = [];
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $collection->addOrder(AuthorInterface::FIRSTNAME, 'ASC');
        /** @var AuthorInterface $author */
        foreach ($collection as $author) {
            $options[$author->getId()] = $author->getFirstname() . ' ' . $author->getLastname();
        }

        return $options;
    }

    /**
     * @inheritDoc
     */
    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
