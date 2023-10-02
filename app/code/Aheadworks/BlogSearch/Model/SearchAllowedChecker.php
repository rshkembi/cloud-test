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

namespace Aheadworks\BlogSearch\Model;

use Magento\Framework\Search\EngineResolverInterface;

class SearchAllowedChecker
{
    public const ALLOWED_ENGINES = ['elasticsearch6', 'elasticsearch7', 'elasticsuite'];

    /**
     * SearchAllowedChecker constructor.
     * @param EngineResolverInterface $engineResolver
     */
    public function __construct(
        private readonly EngineResolverInterface $engineResolver
    ) {
    }

    /**
     * Check if search allowed
     *
     * @returns bool
     */
    public function execute()
    {
        return $this->isCurrentSearchEngineAllowed();
    }

    /**
     * Checks if current search engine allowed
     *
     * @return bool
     */
    private function isCurrentSearchEngineAllowed()
    {
        $currentSearchEngine = $this->engineResolver->getCurrentSearchEngine();

        return in_array($currentSearchEngine, self::ALLOWED_ENGINES);
    }
}
