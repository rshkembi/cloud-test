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
namespace Aheadworks\BlogSearch\Ui\DataProvider\Filter\Builder\Frontend;

use Aheadworks\BlogSearch\Model\Url as BlogSearchUrl;
use Aheadworks\BlogSearch\Ui\DataProvider\Filter\BuilderInterface as FilterBuilderInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Validator\AbstractValidator as Validator;

/**
 * Class SearchQuery
 */
class SearchQuery implements FilterBuilderInterface
{
    const SEARCH_QUERY_FIELD_NAME = 'search_query';

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * SearchQuery constructor.
     * @param FilterBuilder $filterBuilder
     * @param RequestInterface $request
     * @param Validator $validator
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        RequestInterface $request,
        Validator $validator
    ) {
        $this->filterBuilder = $filterBuilder;
        $this->request = $request;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $filter = null;
        $searchQuery = $this->request->getParam(BlogSearchUrl::SEARCH_QUERY_PARAM);

        if ($this->validator->isValid($searchQuery)) {
            $filter = $this->filterBuilder
                ->setConditionType('eq')
                ->setField(self::SEARCH_QUERY_FIELD_NAME)
                ->setValue($searchQuery)
                ->create();
        }

        return $filter;
    }
}
