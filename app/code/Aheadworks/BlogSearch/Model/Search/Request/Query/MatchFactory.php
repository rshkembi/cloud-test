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

namespace Aheadworks\BlogSearch\Model\Search\Request\Query;

use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\Request\QueryInterface;

class MatchFactory
{
    /**
     * @param ObjectManagerInterface $objectManager
     * @param ProductMetadataInterface $productMetadata
     * @param array $instanceName
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly ProductMetadataInterface $productMetadata,
        private readonly array $instanceName = []
    ) {
    }

    /**
     * Create match query instance
     *
     * @param array $data
     * @return QueryInterface
     */
    public function create(array $data): QueryInterface
    {
        $magentoVersion = $this->productMetadata->getVersion();
        $requestQueryMatch = version_compare($magentoVersion, '2.4.4', '>=')
            ? $this->instanceName['match_query']
            : $this->instanceName['match'];

        return $this->objectManager->create($requestQueryMatch, $data);
    }
}
