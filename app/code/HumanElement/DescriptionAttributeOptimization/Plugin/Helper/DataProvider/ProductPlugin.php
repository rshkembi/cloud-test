<?php

namespace HumanElement\DescriptionAttributeOptimization\Plugin\Helper\DataProvider;

use Magento\Catalog\Model\Product;
use MageWorx\SeoMarkup\Helper\DataProvider\Product as DataProviderProduct;

class ProductPlugin
{
    /**
     * @param DataProviderProduct $subject
     * @param $result
     * @param Product $product
     * @return string
     */
    public function afterGetDescriptionValue(DataProviderProduct $subject, $result, Product $product)
    {
        return htmlspecialchars_decode($result);
    }
}
