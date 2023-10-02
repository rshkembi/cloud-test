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

use Aheadworks\BlogGraphQl\Model\ObjectConverter;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\GroupManagement;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\CustomerGraphQl\Model\Customer\GetCustomer;

class PostWithRelatedPosts implements ResolverInterface
{
    /**
     * PostWithRelatedPosts constructor.
     * @param PostRepositoryInterface $postRepository
     * @param GetCustomer $getCustomer
     * @param ArgumentHelper $argumentHelper
     * @param ObjectConverter $converter
     */
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly GetCustomer $getCustomer,
        private readonly ArgumentHelper $argumentHelper,
        private readonly ObjectConverter $converter
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
        $postId = isset($args['postId']) ? $args['postId'] : null;
        $storeId = $this->argumentHelper->getStoreId($args);

        try {
            /** @var CustomerInterface $customer */
            $customer = $this->getCustomer->execute($context);
            $customerGroupId = $customer->getGroupId();
        } catch (\Exception $e) {
            $customerGroupId = GroupManagement::NOT_LOGGED_IN_ID;
        }

        $result = [];
        if (!is_null($storeId) && !is_null($postId) && !is_null($customerGroupId)) {

            $post = $this->postRepository->getWithRelatedPosts($postId, $storeId, $customerGroupId);
            $data['store_id'] = $storeId;
            $data['customer_group'] = $customerGroupId;
            $result = $this->converter->convertToArray($post, PostInterface::class, $data);
        }

        return $result;
    }
}
