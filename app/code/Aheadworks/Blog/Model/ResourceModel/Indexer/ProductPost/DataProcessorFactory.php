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
namespace Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost;

use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor\BatchingProcessor;
use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor\LegacyProcessor;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor\DataProcessorInterface;

/**
 * Class DataProcessorFactory
 *
 * @package Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost
 */
class DataProcessorFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @param ObjectManagerInterface $objectManager
     * @param ProductMetadataInterface $productMetadata
     */
    public function __construct(
        ObjectManagerInterface $objectManager,
        ProductMetadataInterface $productMetadata
    ) {
        $this->objectManager = $objectManager;
        $this->productMetadata = $productMetadata;
    }

    /**
     * Create data processor depending on Magento version
     *
     * @return DataProcessorInterface
     */
    public function create()
    {
        $magentoVersion = $this->productMetadata->getVersion();
        if (version_compare($magentoVersion, '2.2.0', '>=')) {
            $instance = $this->objectManager->create(BatchingProcessor::class);
        } else {
            $instance = $this->objectManager->create(LegacyProcessor::class);
        }

        return $instance;
    }
}
