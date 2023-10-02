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
namespace Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\ObjectManagerInterface;
use Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost;

/**
 * Class ProductPost
 *
 * @package Aheadworks\Blog\Model\ResourceModel\Indexer
 */
class BatchingProcessor
{
    /**
     * Row count to process in a batch
     *
     * @var int
     */
    private $batchRowsCount;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var \Magento\Framework\Indexer\BatchSizeManagementInterface
     */
    private $batchSizeManagement;

    /**
     * @param ResourceConnection $resource
     * @param ObjectManagerInterface $objectManager
     * @param int $batchRowsCount
     */
    public function __construct(
        ResourceConnection $resource,
        ObjectManagerInterface $objectManager,
        $batchRowsCount = 500
    ) {
        $this->resource = $resource;
        $this->objectManager = $objectManager;
        $this->batchRowsCount = $batchRowsCount;
        $this->batchSizeManagement = $this->objectManager->create(
            \Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor\BatchSizeManagement::class
        );
    }

    /**
     * @inheritdoc
     */
    public function insertDataToTable($data, $tableName)
    {
        $counter = 0;
        $toInsert = [];
        $this->batchSizeManagement->ensureBatchSize($this->getConnection(), $this->batchRowsCount);
        foreach ($data as $row) {
            $counter++;
            $toInsert[] = $row;
            if ($counter % $this->batchRowsCount == 0) {
                $this->makeInsert($toInsert, $tableName);
                $toInsert = [];
            }
        }
        $this->makeInsert($toInsert, $tableName);
        return $this;
    }

    /**
     * Make insert to table
     *
     * @param array $dataToInsert
     * @param string $tableName
     * @return $this
     */
    private function makeInsert($dataToInsert, $tableName)
    {
        if (count($dataToInsert)) {
            $this->getConnection()->insertMultiple(
                $this->resource->getTableName($tableName),
                $dataToInsert
            );
        }
        return $this;
    }

    /**
     * Get connection
     *
     * @return AdapterInterface
     */
    private function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = $this->resource->getConnection();
        }
        return $this->connection;
    }
}
