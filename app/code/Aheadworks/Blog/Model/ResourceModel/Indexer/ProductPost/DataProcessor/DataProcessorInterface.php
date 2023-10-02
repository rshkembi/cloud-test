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

/**
 * Interface DataProcessorInterface
 *
 * @package Aheadworks\Blog\Model\Indexer\MultiThread
 */
interface DataProcessorInterface
{
    /**
     * Inset data to table
     *
     * @param array $data
     * @param string $tableName
     */
    public function insertDataToTable($data, $tableName);
}
