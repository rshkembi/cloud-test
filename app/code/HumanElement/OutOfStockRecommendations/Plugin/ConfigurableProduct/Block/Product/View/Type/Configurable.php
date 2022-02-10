<?php

namespace HumanElement\OutOfStockRecommendations\Plugin\ConfigurableProduct\Block\Product\View\Type;

use HumanElement\OutOfStockRecommendations\Helper\Inventory;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Helper\Output;
use Magento\Catalog\Model\Product;

class Configurable
{
    /**
     * @var Json
     */
    protected $serializer;


    /**
     * @var Output
     */
    protected $outputHelper;

    /**
     * @var Inventory
     */
    protected $inventoryHelper;

    /**
     * Configurable constructor.
     * @param Json $serializer
     * @param Output $outputHelper
     * @param Inventory $inventoryHelper
     */
    public function __construct(
        Json $serializer,
        Output $outputHelper,
        Inventory $inventoryHelper
    ) {
        $this->serializer = $serializer;
        $this->outputHelper = $outputHelper;
        $this->inventoryHelper = $inventoryHelper;
    }

    /**
     * Add additional data to configurable product json config
     *
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     * @param $result
     * @return mixed
     */
    public function afterGetJsonConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
        $result
    ) {
        $config = $this->serializer->unserialize($result);
        $config['childProductDetailsConfig'] = $this->getChildProductDetailsConfig($subject);

        return $this->serializer->serialize($config);
    }

    /**
     * Get child product config
     *
     * @param \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
     * @return array
     */
    public function getChildProductDetailsConfig(
        \Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject
    ) {
        $currentProduct = $subject->getProduct();
        $details = [];

        // Configurable product's details
        $details[$currentProduct->getId()] = $this->getChildProductDetail($currentProduct);

        // Each child product's details
        foreach ($subject->getAllowProducts() as $product) {
            $details[$product->getId()] = $this->getChildProductDetail($product);
        }

        return [
            'productDetails' => $details,
            'currentProductId' => $currentProduct->getId()
        ];
    }

    /**
     * Get a single product's detail.
     *
     * @param Product $product
     * @return array
     */
    public function getChildProductDetail(Product $product)
    {
        return [
            'sku' => $this->outputHelper->productAttribute($product, $product->getSku(), 'sku'),
            'name' => $this->outputHelper->productAttribute($product, $product->getName(), 'name'),
            'short_description' => $product->getShortDescription(),
            'description' => $product->getDescription(),
            'stock' => $this->getStockInfo($product),
        ];
    }

    /**
     * Get stock info for product
     *
     * @param Product $product
     * @return array
     */
    public function getStockInfo(Product $product)
    {
        $stockInfo = $this->inventoryHelper->getStockInfo($product);

        return [
            "stockLabel" => __($stockInfo->getMessage()),
            "stockQty" => $stockInfo->getQuantity(),
            "isAvailable" => $stockInfo->getIsAvailable()
        ];
    }
}