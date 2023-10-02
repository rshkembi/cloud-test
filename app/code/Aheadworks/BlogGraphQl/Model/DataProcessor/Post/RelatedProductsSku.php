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

namespace Aheadworks\BlogGraphQl\Model\DataProcessor\Post;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\BlogGraphQl\Model\DataProcessor\DataProcessorInterface;
use Aheadworks\Blog\Model\Post\RelatedProduct\Provider as RelatedProductProvider;

class RelatedProductsSku implements DataProcessorInterface
{
    /**
     * @param RelatedProductProvider $relatedProductProvider
     */
    public function __construct(private readonly RelatedProductProvider $relatedProductProvider)
    {
    }

    /**
     * Process data array before send
     *
     * @param array $data
     * @param array $args
     * @return array
     */
    public function process(array $data, array $args): array
    {
        /** @var PostInterface $post */
        $post = $data['model'];
        $storeId = $args['store_id'];
        $relatedProductsSku = [];
        foreach ($this->relatedProductProvider->getAvailableProductList($post, $storeId) as $product) {
            $relatedProductsSku[] = $product->getSku();
        }
        $data['related_products_sku'] = $relatedProductsSku;

        return $data;
    }
}
