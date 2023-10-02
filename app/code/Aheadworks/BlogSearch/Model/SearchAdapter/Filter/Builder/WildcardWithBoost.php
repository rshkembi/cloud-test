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
namespace Aheadworks\BlogSearch\Model\SearchAdapter\Filter\Builder;

use Magento\Framework\Search\Request\Filter\Wildcard as WildcardFilterRequest;
use Magento\Framework\Search\Request\FilterInterface as RequestFilterInterface;
use Magento\Elasticsearch\Model\Adapter\FieldMapperInterface;
use Magento\Elasticsearch\SearchAdapter\Filter\Builder\FilterInterface;
use Aheadworks\BlogSearch\Model\SearchAdapter\BoostResolver;

class WildcardWithBoost implements FilterInterface
{
    /**
     * @var FieldMapperInterface
     */
    private $fieldMapper;

    /**
     * @var BoostResolver
     */
    private $boostResolver;

    /**
     * WildcardWithBoost constructor.
     * @param BoostResolver $boostResolver
     * @param FieldMapperInterface $fieldMapper
     */
    public function __construct(
        BoostResolver $boostResolver,
        FieldMapperInterface $fieldMapper
    ) {
        $this->boostResolver = $boostResolver;
        $this->fieldMapper = $fieldMapper;
    }

    /**
     * @inheritdoc
     * @param RequestFilterInterface|WildcardFilterRequest $filter
     * @return array
     */
    public function buildFilter(RequestFilterInterface $filter)
    {
        $fieldName = $this->fieldMapper->getFieldName($filter->getField());
        return [
            [
                'wildcard' => [
                    $fieldName => [
                        'value' => '*' . $filter->getValue() . '*',
                        'boost' => $this->boostResolver->getBoostByQueryName($filter->getName()),
                    ]
                ],
            ]
        ];
    }
}
