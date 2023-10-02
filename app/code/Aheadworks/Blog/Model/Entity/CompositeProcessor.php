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
declare(strict_types=1);

namespace Aheadworks\Blog\Model\Entity;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class CompositeProcessor implements ProcessorInterface
{
    /**
     * @param ProcessorInterface[] $processorList
     */
    public function __construct(private readonly array $processorList = [])
    {
    }

    /**
     * Process entity instance
     *
     * @param AbstractModel $entity
     * @return AbstractModel
     * @throws LocalizedException
     */
    public function process($entity)
    {
        foreach ($this->processorList as $processor) {
            if (!$processor instanceof ProcessorInterface) {
                throw new ConfigurationMismatchException(
                    __('Entity processor must implement %1', ProcessorInterface::class)
                );
            }
            $entity = $processor->process($entity);
        }
        return $entity;
    }
}
