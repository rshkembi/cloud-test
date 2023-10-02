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
 * @package    Popup
 * @version    1.2.9
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
declare(strict_types=1);

namespace Aheadworks\Popup\Model\Rule\Condition\Product;

use Magento\Backend\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection;
use Magento\Framework\Locale\FormatInterface;
use Magento\Rule\Model\Condition\Context;
use Magento\Rule\Model\Condition\Product\AbstractProduct;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;

/**
 * Class Attributes
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Attributes extends AbstractProduct
{
    /**
     * @var array
     */
    protected $joinedAttributes = [];

    /**
     * Store manager
     * @var StoreManager
     */
    protected $storeManager;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Data $backendData
     * @param Config $config
     * @param ProductFactory $productFactory
     * @param ProductRepositoryInterface $productRepository
     * @param Product $productResource
     * @param Collection $attrSetCollection
     * @param FormatInterface $localeFormat
     * @param StoreManager $storeManager
     * @param array $data
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Data $backendData,
        Config $config,
        ProductFactory $productFactory,
        ProductRepositoryInterface $productRepository,
        Product $productResource,
        Collection $attrSetCollection,
        FormatInterface $localeFormat,
        StoreManager $storeManager,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $backendData,
            $config,
            $productFactory,
            $productRepository,
            $productResource,
            $attrSetCollection,
            $localeFormat,
            $data
        );
        $this->storeManager = $storeManager;
        $this->setType(\Aheadworks\Popup\Model\Rule\Condition\Product\Attributes::class);
        $this->setValue(null);
    }

    /**
     * Prepare child rules option list
     *
     * @return array
     */
    public function getNewChildSelectOptions(): array
    {
        $attributes = $this->loadAttributeOptions()->getAttributeOption();
        $conditions = [];
        foreach ($attributes as $code => $label) {
            $conditions[] = ['value' => $this->getType() . '|' . $code, 'label' => $label];
        }

        return ['value' => $conditions, 'label' => __('Product Attributes')];
    }

    /**
     * @inheritdoc
     *
     * @param ProductCollection $productCollection
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function collectValidatedAttributes($productCollection): Attributes
    {
        return $this->addToCollection($productCollection);
    }

    /**
     * Add condition to collection
     *
     * @param ProductCollection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function addToCollection(ProductCollection $collection): Attributes
    {
        $attribute = $this->getAttributeObject();
        $attributeCode = $attribute->getAttributeCode();
        if ($attributeCode !== 'price' || !$collection->getLimitationFilters()->isUsingPriceIndex()) {
            if ($collection->isEnabledFlat()) {
                if ($attribute->isEnabledInFlat()) {
                    $alias = array_keys($collection->getSelect()->getPart('from'))[0];
                    $this->joinedAttributes[$attributeCode] = $alias . '.' . $attributeCode;
                } else {
                    $alias = 'at_' . $attributeCode;
                    if (!array_key_exists($alias, $collection->getSelect()->getPart('from'))) {
                        $collection->joinAttribute($attributeCode, "catalog_product/$attributeCode", 'entity_id');
                    }
                    $this->joinedAttributes[$attributeCode] = $alias . '.value';
                }
            } elseif ($attributeCode !== 'category_ids' && !$attribute->isStatic()) {
                $this->addAttributeToCollection($attribute, $collection);
                $attributes = $this->getRule()->getCollectedAttributes();
                $attributes[$attributeCode] = true;
                $this->getRule()->setCollectedAttributes($attributes);
            }
        } else {
            $this->joinedAttributes['price'] ='price_index.min_price';
        }

        return $this;
    }

    /**
     * Adds Attributes that belong to Global Scope
     *
     * @param Attribute $attribute
     * @param ProductCollection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function addGlobalAttribute(
        Attribute $attribute,
        ProductCollection $collection
    ): Attributes {
        switch ($attribute->getBackendType()) {
            case 'decimal':
            case 'datetime':
            case 'int':
                $alias = 'at_' . $attribute->getAttributeCode();
                $collection->addAttributeToSelect($attribute->getAttributeCode(), 'inner');
                break;
            default:
                $alias = 'at_' . sha1($this->getId()) . $attribute->getAttributeCode();

                $connection = $this->_productResource->getConnection();
                $storeId = $connection->getIfNullSql(
                    $alias . '.store_id', $this->storeManager->getStore()->getId()
                );
                $linkField = $attribute->getEntity()->getLinkField();

                $collection->getSelect()->join(
                    [$alias => $collection->getTable($attribute->getBackendTable())],
                    "($alias.$linkField = e.$linkField) AND ($alias.store_id = $storeId)" .
                    " AND ($alias.attribute_id = {$attribute->getId()})",
                    []
                );
        }

        $this->joinedAttributes[$attribute->getAttributeCode()] = $alias . '.value';

        return $this;
    }

    /**
     * Adds Attributes that don't belong to Global Scope
     *
     * @param Attribute $attribute
     * @param ProductCollection $collection
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function addNotGlobalAttribute(
        Attribute $attribute,
        ProductCollection $collection
    ): Attributes {
        $storeId = $this->storeManager->getStore()->getId();
        $values = $collection->getAllAttributeValues($attribute->getAttributeCode());

        $validEntities = [];
        if ($values) {
            foreach ($values as $entityId => $storeValues) {
                if (isset($storeValues[$storeId])) {
                    if ($this->validateAttribute($storeValues[$storeId])) {
                        $validEntities[] = $entityId;
                    }
                } else {
                    if (isset($storeValues[Store::DEFAULT_STORE_ID]) &&
                        $this->validateAttribute($storeValues[Store::DEFAULT_STORE_ID])
                    ) {
                        $validEntities[] = $entityId;
                    }
                }
            }
        }
        $this->setOperator('()');
        $this->unsetData('value_parsed');
        if ($validEntities) {
            $this->setData('value', implode(',', $validEntities));
        } else {
            $this->unsetData('value');
        }

        return $this;
    }

    /**
     * Get mapped sql field
     *
     * @return string
     */
    public function getMappedSqlField(): string
    {
        $result = '';
        if (in_array($this->getAttribute(), ['category_ids', 'sku', 'attribute_set_id'])) {
            $result = parent::getMappedSqlField();
        } elseif (isset($this->joinedAttributes[$this->getAttribute()])) {
            $result = $this->joinedAttributes[$this->getAttribute()];
        } elseif ($this->getAttributeObject()->isStatic()) {
            $result = $this->getAttributeObject()->getAttributeCode();
        } elseif ($this->getValueParsed()) {
            $result = 'e.entity_id';
        }

        return $result;
    }

    /**
     * Add attribute to collection based on scope
     *
     * @param Attribute $attribute
     * @param ProductCollection $collection
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function addAttributeToCollection($attribute, $collection): void
    {
        if ($attribute->getBackend() && $attribute->isScopeGlobal()) {
            $this->addGlobalAttribute($attribute, $collection);
        } else {
            $this->addNotGlobalAttribute($attribute, $collection);
        }
    }

    /**
     * Get argument value to bind
     *
     * @return array|float|int|mixed|string|\Zend_Db_Expr
     */
    public function getBindArgumentValue()
    {
        $value = parent::getBindArgumentValue();
        return is_array($value) && $this->getMappedSqlField() === 'e.entity_id'
            ? new \Zend_Db_Expr(
                $this->_productResource->getConnection()->quoteInto('?', $value, \Zend_Db::INT_TYPE)
            )
            : $value;
    }
}
