<?php
namespace HumanElement\OutOfStockRecommendations\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\InventoryCatalogApi\Api\DefaultStockProviderInterface;
use Magento\InventoryConfigurationApi\Api\GetStockItemConfigurationInterface;
use Magento\InventorySalesApi\Api\IsProductSalableInterface;
use Magento\InventorySalesApi\Api\GetProductSalableQtyInterface;
use Magento\CatalogInventory\Model\Stock;
use Magento\Catalog\Api\Data\ProductInterface;

class Inventory extends AbstractHelper
{
    /**
     * @var DefaultStockProviderInterface
     */
    protected $defaultStockProvider;

    /**
     * @var IsProductSalableInterface
     */
    protected $isProductSalable;

    /**
     * @var GetProductSalableQtyInterface
     */
    protected $getProductSalableQty;

    /**
     * @var GetStockItemConfigurationInterface
     */
    protected $stockConfiguration;

    /**
     * Inventory constructor.
     * @param Context $context
     * @param DefaultStockProviderInterface $defaultStockProvider
     * @param IsProductSalableInterface $isProductSalable
     * @param GetProductSalableQtyInterface $getProductSalableQty
     * @param GetStockItemConfigurationInterface $stockConfiguration
     */
    public function __construct(
        Context $context,
        DefaultStockProviderInterface $defaultStockProvider,
        IsProductSalableInterface $isProductSalable,
        GetProductSalableQtyInterface $getProductSalableQty,
        GetStockItemConfigurationInterface $stockConfiguration
    ) {
        parent::__construct($context);

        $this->defaultStockProvider = $defaultStockProvider;
        $this->isProductSalable = $isProductSalable;
        $this->getProductSalableQty = $getProductSalableQty;
        $this->stockConfiguration = $stockConfiguration;
    }

    /**
     * Get the salable status of a product.
     * The default stock will be used if not specified.
     *
     * @param ProductInterface $product
     * @param null $stockId
     * @return bool
     */
    public function getProductIsSalable(ProductInterface $product, $stockId = null)
    {
        if (is_null($stockId)) {
            $stockId = $this->defaultStockProvider->getId();
        }

        return $this->isProductSalable->execute($product->getSku(), $stockId);
    }

    /**
     * Get the salable/in-stock quantity of a product.
     * The default stock will be used if not specified.
     *
     * @param ProductInterface $product
     * @param int|null $stockId
     * @return float|int
     */
    public function getProductSalableQuantity(ProductInterface $product, $stockId = null)
    {
        if (is_null($stockId)) {
            $stockId = $this->defaultStockProvider->getId();
        }

        try {
            $quantity = $this->getProductSalableQty->execute($product->getSku(), $stockId);
        } catch (\Exception $e) {
            $quantity = 0;
        }

        return $quantity;
    }

    /**
     * Get the backorder configuration for a product.
     * The default stock will be used if not specified.
     *
     * @param $product
     * @param int|null $stockId
     * @return int
     */
    public function getProductBackorderStatus(ProductInterface $product, $stockId = null)
    {
        if (is_null($stockId)) {
            $stockId = $this->defaultStockProvider->getId();
        }

        try {
            $stockConfig = $this->stockConfiguration->execute(
                $product->getSku(),
                $stockId
            );
        } catch (\Exception $e) {
            return Stock::BACKORDERS_NO;
        }

        return $stockConfig->getBackorders();
    }

    /**
     * Retrieve product stock availability status, saleable quantity, and the stock message to display.
     *
     * @param ProductInterface $product
     * @return DataObject
     */
    public function getStockInfo(ProductInterface $product)
    {
        $isSaleable = $this->getProductIsSalable($product);
        if ($isSaleable && $product->getTypeId() === 'simple') {
            $salableQuantity = $this->getProductSalableQuantity($product);
        } else {
            $salableQuantity = 0;
        }
        $backorderStatus = $this->getProductBackorderStatus($product);
        $isAvailable = $isSaleable && $product->isAvailable();
        $customStockMessageAttr = $product->getCustomAttribute('snt_stock_message');
        $customStockMessage = $customStockMessageAttr ? $customStockMessageAttr->getValue() : '';

        if ($isAvailable) {
            switch ($product->getTypeId()) {
                case 'configurable':
                    $message = '';
                    break;
                case 'simple':
                    if ($salableQuantity <= 0 &&
                        ($backorderStatus == Stock::BACKORDERS_YES_NONOTIFY
                            || $backorderStatus == Stock::BACKORDERS_YES_NOTIFY)
                    ) {
                        $message = 'Backorder';
                    }
                    elseif ($salableQuantity > 100) {
                        $message = 'Over 100 Available';
                    } else {
                        $message = $salableQuantity . ' Available';
                    }
                    break;
                default:
                    $message = 'In Stock';
                    break;
            }
        } elseif ($customStockMessage) {
            $message = $customStockMessage;
        } else {
            $message = 'Out of stock';
        }

        $info = new DataObject();
        $info->setIsAvailable($isAvailable);
        $info->setQuantity($salableQuantity);
        $info->setMessage($message);

        return $info;
    }
}