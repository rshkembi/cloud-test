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

namespace Aheadworks\BlogGraphQl\Model\Resolver;

use Aheadworks\Blog\Api\Data\CommentInterface;
use Aheadworks\Blog\Model\Post\Comment\Provider;
use Aheadworks\Blog\Model\Post\Comment\SearchCriteria\Resolver;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;

class PostComments implements ResolverInterface
{
    /**
     * @param Provider $commentProvider
     * @param ArgumentHelper $argumentHelper
     */
    public function __construct(
        private readonly Provider $commentProvider,
        private readonly ArgumentHelper $argumentHelper
    ) {
    }

    /**
     * Fetches the data from persistence models and format it according to the GraphQL schema.
     *
     * @param Field $field
     * @param ContextInterface $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @throws \Exception
     * @return array
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $postId = $args['postId'] ?? null;
        $storeId = $this->argumentHelper->getStoreId($args);
        $data = $this->prepareData($args);

        $result = [];
        if (!is_null($storeId) && !is_null($postId)) {
            $result = $this->commentProvider->getComments($data);
        }

        return $result;
    }

    /**
     * Prepare data
     *
     * @param array $args
     * @return array
     */
    private function prepareData(array $args): array
    {
        $data = [];
        $data[CommentInterface::POST_ID] = $args['postId'];
        $data[CommentInterface::STORE_ID] = $this->argumentHelper->getStoreId($args);
        $data[Resolver::CURRENT_PAGE] = $args['currentPage'];
        $data[Resolver::ROOT_COMMENT_QTY] = $args['rootCommentPageSize'];
        $data[Resolver::CHILD_COMMENT_QTY] = $args['replyCommentPageSize'];
        $data[Resolver::SORT_ORDER_DIRECTION] = $args['sort']['created_at'] ?? null;

        return $data;
    }
}
