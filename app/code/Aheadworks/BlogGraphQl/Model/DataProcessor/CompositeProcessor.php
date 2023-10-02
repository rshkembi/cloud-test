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

class CompositeProcessor
{
    /**
     * @param DataProcessorInterface[] $processors
     */
    public function __construct(private readonly array $processors)
    {
    }

    /**
     * Process data
     *
     * @param array $data
     * @param array $args
     * @return array
     */
    public function process(array $data, array $args = []): array
    {
        foreach ($this->processors as $processor) {
            $data = $processor->process($data, $args);
        }

        return $data;
    }
}
