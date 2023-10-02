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

/**
 * Class LegacyProcessor
 *
 * @package Aheadworks\Blog\Model\ResourceModel\Indexer\ProductPost\DataProcessor
 */
class LegacyProcessor implements DataProcessorInterface
{
    /**
     * @var int
     */
    const INSERT_PER_QUERY = 500;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * @inheritdoc
     */
    public function insertDataToTable($data, $tableName)
    {
        $counter = 0;
        $toInsert = [];
        foreach ($data as $row) {
            $counter++;
            $toInsert[] = $row;
            if ($counter % self::INSERT_PER_QUERY == 0) {
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
