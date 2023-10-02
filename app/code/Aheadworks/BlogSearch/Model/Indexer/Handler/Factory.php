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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\BlogSearch\Model\Indexer\Handler;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\EngineResolverInterface;

class Factory
{
    /**
     * @param ObjectManagerInterface $objectManager
     * @param EngineResolverInterface $engineResolver
     * @param array $handlers
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly EngineResolverInterface $engineResolver,
        private readonly array $handlers = []
    ) {
    }

    /**
     * Create handler
     *
     * @return
     */
    public function create()
    {
        $currentSearchEngine = $this->engineResolver->getCurrentSearchEngine();
        $instance = null;
        if ($this->handlers[$currentSearchEngine]) {
            $instance = $this->objectManager->create($this->handlers[$currentSearchEngine]);
        }

        return $instance;
    }
}
