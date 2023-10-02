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

namespace Aheadworks\BlogGraphQl\Model\DataProcessor;

class Pool
{
    /**
     * @param DataProcessorInterface[] $processors
     */
    public function __construct(private readonly array $processors = [])
    {
    }

    /**
     * Get data processor for instance
     *
     * @param string $instanceName
     * @return DataProcessorInterface|null
     */
    public function getForInstance(string $instanceName)
    {
        return $this->processors[$instanceName] ?? null;
    }
}
