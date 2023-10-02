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
 * @package    Blog
 * @version    2.17.1
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Blog\Test\Unit\Model\Plugin;

use Aheadworks\Blog\Model\Plugin\Product;
use Magento\CatalogRule\Model\Rule\Condition\Product as BlogProductAttributes;
use Aheadworks\Blog\Model\Indexer\ProductPost\Processor as ProductPostProcessor;
use Magento\Framework\Indexer\StateInterface;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Blog\Model\Plugin\Product
 */
class ProductTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Product
     */
    private $model;

    /**
     * @var BlogProductAttributes|\PHPUnit_Framework_MockObject_MockObject
     */
    private $blogProductAttributesMock;

    /**
     * @var StateInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $indexerStateMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->blogProductAttributesMock = $this->getMockBuilder(BlogProductAttributes::class)
            ->setMethods(['loadAttributeOptions', 'getAttributeOption'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->indexerStateMock = $this->getMockForAbstractClass(
            StateInterface::class,
            [],
            '',
            false,
            true,
            true,
            ['save']
        );

        $this->model = $objectManager->getObject(
            Product::class,
            [
                'blogProductAttributes' => $this->blogProductAttributesMock,
                'indexerState' => $this->indexerStateMock
            ]
        );
    }

    /**
     * Testing of beforeSave method
     */
    public function testBeforeSave()
    {
        $productClimate = ['All-Weather', 'Cold'];
        $attributeOption = ['climate' => 'Climate'];
        $productMock = $this->getMockBuilder(ProductModel::class)
            ->setMethods(['getId', 'getOrigData'])
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->expects($this->once())
            ->method('getOrigData')
            ->with('climate')
            ->willReturn($productClimate);
        $this->blogProductAttributesMock->expects($this->once())
            ->method('loadAttributeOptions')
            ->willReturnSelf();
        $this->blogProductAttributesMock->expects($this->once())
            ->method('getAttributeOption')
            ->willReturn($attributeOption);

        $this->model->beforeSave($productMock);
    }

    /**
     * Testing of afterSave method
     */
    public function testAfterSave()
    {
        $productClimate = ['All-Weather', 'Cold'];
        $attributeOption = ['climate' => 'Climate'];

        $productMock = $this->getMockBuilder(ProductModel::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();
        $productMock->expects($this->once())
            ->method('getData')
            ->with('climate')
            ->willReturn($productClimate);
        $this->blogProductAttributesMock->expects($this->once())
            ->method('loadAttributeOptions')
            ->willReturnSelf();
        $this->blogProductAttributesMock->expects($this->once())
            ->method('getAttributeOption')
            ->willReturn($attributeOption);
        $this->indexerStateMock->expects($this->once())
            ->method('loadByIndexer')
            ->with(ProductPostProcessor::INDEXER_ID)
            ->willReturnSelf();
        $this->indexerStateMock->expects($this->once())
            ->method('setStatus')
            ->with(StateInterface::STATUS_INVALID)
            ->willReturnSelf();
        $this->indexerStateMock->expects($this->once())
            ->method('save')
            ->willReturnSelf();

        $this->model->afterSave($productMock, $productMock);
    }
}
