<?php

namespace HumanElement\ShowConfigurableOutOfStockPrice\Pricing\Price;

use Magento\ConfigurableProduct\Pricing\Price\ConfigurablePriceResolver;
use Magento\Framework\Pricing\SaleableInterface;

class ConfigurablePriceResolverPlugin
{
    /**
     * Show configurable product min price
     * when all its options are out of stock
     *
     * @param ConfigurablePriceResolver $source
     * @param float $result
     * @param SaleableInterface $product
     * @return float
     */
    public function afterResolvePrice(ConfigurablePriceResolver $source, float $result, SaleableInterface $product)
    {
        if (!$result) {
            $result = null;
            $children = $product->getTypeInstance()->getUsedProducts($product);
            foreach ($children as $child) {
                $result = isset($result) ? min($result, $child->getPrice()) : $child->getPrice();
            }
        }
        return $result;
    }
}
