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

namespace Aheadworks\BlogSearch\Plugin\Elasticsearch\SearchAdapter\Query\Builder;

use Aheadworks\BlogSearch\Model\SearchAdapter\Query\Builder\MatchQueryBoostApplier;
use Magento\Elasticsearch\SearchAdapter\Query\Builder\QueryInterface;
use Magento\Framework\Search\Request\QueryInterface as RequestQueryInterface;

/**
 * Class MatchPlugin
 */
class MatchPlugin
{
    /**
     * @var MatchQueryBoostApplier
     */
    private $matchQueryBoostApplier;

    /**
     * MatchPlugin constructor.
     *
     * @param MatchQueryBoostApplier $matchQueryBoostApplier
     */
    public function __construct(
        MatchQueryBoostApplier $matchQueryBoostApplier
    ) {
        $this->matchQueryBoostApplier = $matchQueryBoostApplier;
    }

    /**
     * Adds boost to match queries
     *
     * @param  QueryInterface        $subject
     * @param  array                 $selectQuery
     * @param  RequestQueryInterface $requestQuery
     * @param  string                $conditionType
     * @return array
     */
    public function beforeBuild(
        QueryInterface $subject,
        array $selectQuery,
        RequestQueryInterface $requestQuery,
        $conditionType
    ) {
        $requestQuery = $this->matchQueryBoostApplier->apply($requestQuery);
        return [$selectQuery, $requestQuery, $conditionType];
    }
}
