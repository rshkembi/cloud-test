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
namespace Aheadworks\Blog\Model\Indexer\Batch;

/**
 * Class Resolver
 */
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Indexer\BatchSizeManagementInterface;
use Magento\Framework\DB\Adapter\AdapterInterface as DBAdapterInterface;

class Resolver
{
    /**
     * Deployment config path
     *
     * @var string
     */
    private const DEPLOYMENT_CONFIG_INDEXER_BATCHES = 'indexer/batch_size/';

    /**
     * @var DeploymentConfig
     */
    private $deploymentConfig;

    /**
     * @var BatchSizeManagementInterface
     */
    private $batchSizeManagement;

    /**
     * @var string|null
     */
    private $indexerId;

    /**
     * @var int|null
     */
    private $defaultBatchRowCount;

    /**
     * @param DeploymentConfig $deploymentConfig
     * @param BatchSizeManagementInterface $batchSizeManagement
     * @param string|null $indexerId
     * @param int|null $defaultBatchRowCount
     */
    public function __construct(
        DeploymentConfig $deploymentConfig,
        BatchSizeManagementInterface $batchSizeManagement,
        string $indexerId = null,
        int $defaultBatchRowCount = null
    ) {
        $this->deploymentConfig = $deploymentConfig;
        $this->batchSizeManagement = $batchSizeManagement;
        $this->indexerId = $indexerId;
        $this->defaultBatchRowCount = $defaultBatchRowCount;
    }

    /**
     * Retrieve ensured batch row count
     *
     * @param DBAdapterInterface $connection
     * @return int
     * @throws \Exception
     */
    public function getBatchRowCount($connection)
    {
        $batchRowCount = (int) (
            $this->deploymentConfig->get(
                self::DEPLOYMENT_CONFIG_INDEXER_BATCHES . $this->indexerId
            ) ?? $this->defaultBatchRowCount
        );
            $this->batchSizeManagement->ensureBatchSize($connection, $batchRowCount);

        return $batchRowCount;
    }
}