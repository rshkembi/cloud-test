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
 * @package    BlogGraphQl
 * @version    1.2.2
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogGraphQl\Model;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\BlogGraphQl\Model\DataProcessor\Pool as ProcessorsPool;
use Magento\Framework\Reflection\DataObjectProcessor;

class ObjectConverter
{
    /**
     * @param DataObjectProcessor $dataObjectProcessor
     * @param ProcessorsPool $processorsPool
     */
    public function __construct(
        private readonly DataObjectProcessor $dataObjectProcessor,
        private readonly ProcessorsPool $processorsPool
    ) {
    }

    /**
     * Convert object to array with processing
     *
     * @param PostInterface $object
     * @param string $instanceName
     * @param array $args
     * @return array
     */
    public function convertToArray($object, string $instanceName, array $args): array
    {
        $data = $this->dataObjectProcessor->buildOutputDataArray(
            $object,
            $instanceName
        );
        $data['model'] = $object;

        $dataArrayProcessor = $this->processorsPool->getForInstance($instanceName);
        if ($dataArrayProcessor) {
            $data = $dataArrayProcessor->process($data, $args);
        }

        return $data;
    }
}
