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

namespace Aheadworks\BlogSearch\Model\ThirdPartyModule\ElasticSuite;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Aheadworks\BlogSearch\Model\ResourceModel\Post\Fulltext\Collection;

class Adapter
{
    /**
     * @param ObjectManagerInterface $objectManager
     * @param RequestInterface $request
     * @param StoreManagerInterface $storeManager
     * @param array $instanceName
     */
    public function __construct(
        private readonly ObjectManagerInterface $objectManager,
        private readonly RequestInterface $request,
        private readonly StoreManagerInterface $storeManager,
        private readonly array $instanceName = []
    ) {
    }

    /**
     * Get request
     *
     * @param Collection $collection
     * @param string $searchRequestName
     * @return \Smile\ElasticsuiteCore\Search\RequestInterface
     * @throws NoSuchEntityException
     */
    public function getRequest(Collection $collection, string $searchRequestName)
    {
        $storeId = $this->storeManager->getStore()->getId();

        // Pagination params.
        $size = $collection->getPageSize();
        $from = $size * (max(1, $collection->getCurPage()) - 1);

        $sortOrders = [];
        $requestBuilder = $this->objectManager->create($this->instanceName['builder']);
        $searchRequest = $requestBuilder->create(
            $storeId,
            $searchRequestName,
            $from,
            $size,
            $this->getQueryText(),
            $sortOrders,
            [],
            [],
            [],
            false
        );

        return $searchRequest;
    }

    /**
     * Get docs
     *
     * @param \Smile\ElasticsuiteCore\Search\Adapter\Elasticsuite\Response\QueryResponse $queryResponse
     * @return array
     * @throws \Exception
     */
    public function getDocs($queryResponse): array
    {
        // Filter search results. The pagination has to be resetted since it is managed by the engine itself.
        $docIds = array_map(
            function (\Magento\Framework\Api\Search\Document $doc) {
                return (int)$doc->getId();
            },
            $queryResponse->getIterator()->getArrayCopy()
        );

        return $docIds;
    }

    /**
     * List of search terms suggested by the search terms data provider.
     *
     * @return array
     */
    private function getQueryText(): array
    {
        $terms = [];
        if ($query = $this->request->getParam('search')) {
            $terms = [$query];
        }

        return $terms;
    }
}
