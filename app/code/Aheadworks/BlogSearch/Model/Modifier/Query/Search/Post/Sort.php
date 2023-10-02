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
namespace Aheadworks\BlogSearch\Model\Modifier\Query\Search\Post;

use Aheadworks\BlogSearch\Model\Indexer\Post\Fulltext;
use Magento\Framework\Search\RequestInterface;
use Aheadworks\BlogSearch\Model\Modifier\Query\Search\ModifierInterface;

/**
 * Class Sort
 */
class Sort implements ModifierInterface
{
    /**
     * @inheritdoc
     */
    public function modify(array $searchQuery, RequestInterface $request)
    {
        if ($request->getName() == Fulltext::INDEXER_ID) {
            $searchQuery['body']['sort'] = [
                [
                    '_score' => [
                        'order' => 'desc',
                    ]
                ]
            ];
        }

        return $searchQuery;
    }
}
