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
declare(strict_types=1);

namespace Aheadworks\Blog\Model\Layout;

use Magento\Framework\ObjectManagerInterface;

class LayoutProcessorProvider implements LayoutProcessorProviderInterface
{
    /**
     * @var LayoutProcessorInterface[]
     */
    private array $metadataInstances = [];

    /**
     * @param ObjectManagerInterface $objectManager
     * @param array $processors
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly array $processors = []
    ) {
    }

    /**
     * Retrieves array of layout processors
     *
     * @return LayoutProcessorInterface[]
     */
    public function getLayoutProcessors(): array
    {
        if (empty($this->metadataInstances)) {
            foreach ($this->processors as $layoutProcessorClassName) {
                $metadataInstance = $this->objectManager->create($layoutProcessorClassName);
                if ($metadataInstance instanceof LayoutProcessorInterface) {
                    $this->metadataInstances[$layoutProcessorClassName] = $metadataInstance;
                }
            }
        }

        return $this->metadataInstances;
    }
}
