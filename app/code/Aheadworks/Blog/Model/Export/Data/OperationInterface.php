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
namespace Aheadworks\Blog\Model\Export\Data;

use Magento\Framework\Exception\LocalizedException;
use Magento\ImportExport\Api\Data\ExtendedExportInfoInterface;

/**
 * Interface OperationInterface
 */
interface OperationInterface
{
    /**
     * Perform operation over entity data
     *
     * @param array $entityData
     * @return ExtendedExportInfoInterface
     * @throws LocalizedException
     */
    public function execute($entityData);
}