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
namespace Aheadworks\Blog\Model\Indexer\MultiThread;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class PostDimensionFactory
 *
 * @package Aheadworks\Blog\Model\Indexer\MultiThread
 */
class PostDimensionFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create post dimension instance
     *
     * @param string $name
     * @param array $value
     * @return PostDimension
     */
    public function create($name, $value)
    {
        return $this->objectManager->create(
            PostDimension::class,
            [
                'name' => $name,
                'value' => $value,
            ]
        );
    }
}
