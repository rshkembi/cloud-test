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
namespace Aheadworks\BlogGraphQl\Model\Resolver\DataProvider;

use Magento\Framework\ObjectManagerInterface;

/**
 * Class SearchResultFactory
 * @package Aheadworks\BlogGraphQl\Model\Resolver\DataProvider
 */
class SearchResultFactory
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * Create SearchResult
     *
     * @param int $totalCount
     * @param array $items
     * @return SearchResult
     */
    public function create(int $totalCount, array $items) : SearchResult
    {
        return $this->objectManager->create(
            SearchResult::class,
            ['totalCount' => $totalCount, 'items' => $items]
        );
    }
}
