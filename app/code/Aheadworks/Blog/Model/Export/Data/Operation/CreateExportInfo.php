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
namespace Aheadworks\Blog\Model\Export\Data\Operation;

use Aheadworks\Blog\Model\Export\Data\OperationInterface;
use Aheadworks\Blog\Model\Export\InfoFactory as ExportInfoFactory;

/**
 * Class CreateExportInfo
 */
class CreateExportInfo implements OperationInterface
{
    /**
     * CreateExportInfo constructor.
     * @param ExportInfoFactory $exportInfoFactory
     */
    public function __construct(
        private ExportInfoFactory $exportInfoFactory
    ) {
    }

    const FORMAT_FILE = 'csv';

    /**
     * @inheritDoc
     */
    public function execute($entityData)
    {
        /** @var ExportInfoFactory $dataObject */
        $dataObject = $this->exportInfoFactory->create(
            self::FORMAT_FILE,
            $entityData['entity'],
            $entityData['export_filter'],
            $entityData['skip_attr'] ?? [],
            null
        );

        return $dataObject;
    }
}
