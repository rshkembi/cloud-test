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

use Magento\Framework\GraphQl\Query\Resolver\TypeResolverInterface;

class CategoryTypeResolver implements TypeResolverInterface
{
    public const CATEGORY = 'AW_BLOG_CATEGORY';
    public const TYPE_RESOLVER = 'AwBlogCategory';

    /**
     * Determine a concrete GraphQL type based off the given data.
     *
     * @param array $data
     * @return string
     */
    public function resolveType(array $data) : string
    {
        if (isset($data['type_id']) && $data['type_id'] === self::CATEGORY) {
            return self::TYPE_RESOLVER;
        }

        return '';
    }
}
