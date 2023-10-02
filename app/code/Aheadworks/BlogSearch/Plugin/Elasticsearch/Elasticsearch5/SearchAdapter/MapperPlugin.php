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
namespace Aheadworks\BlogSearch\Plugin\Elasticsearch\Elasticsearch5\SearchAdapter;

use Magento\Framework\Search\RequestInterface;
use Magento\Elasticsearch\Elasticsearch5\SearchAdapter\Mapper;
use Aheadworks\BlogSearch\Model\Modifier\Query\Search\ModifierInterface as SearchQueryModifier;

/**
 * Class MapperPlugin
 */
class MapperPlugin
{
    /**
     * @var SearchQueryModifier
     */
    private $searchQueryModifier;

    /**
     * MapperPlugin constructor.
     * @param SearchQueryModifier $searchQueryModifier
     */
    public function __construct(
        SearchQueryModifier $searchQueryModifier = null
    ) {
        $this->searchQueryModifier = $searchQueryModifier;
    }

    /**
     * Modify posts search query
     * @param Mapper $subject
     * @param $result
     * @param RequestInterface $request
     * @return mixed
     */
    public function afterBuildQuery(Mapper $subject, $searchQuery, RequestInterface $request)
    {
        if ($this->searchQueryModifier) {
            $searchQuery = $this->searchQueryModifier->modify($searchQuery, $request);
        }

        return $searchQuery;
    }
}
