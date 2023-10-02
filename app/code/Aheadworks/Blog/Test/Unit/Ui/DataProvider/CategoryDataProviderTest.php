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
namespace Aheadworks\Blog\Test\Unit\Ui\DataProvider;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Ui\DataProvider\CategoryDataProvider;
use Aheadworks\Blog\Model\ResourceModel\Category\Grid\CollectionFactory;
use Aheadworks\Blog\Model\ResourceModel\Category\Grid\Collection;
use Aheadworks\Blog\Model\Category;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Test for \Aheadworks\Blog\Ui\DataProvider\CategoryDataProvider
 */
class CategoryDataProviderTest extends \PHPUnit\Framework\TestCase
{
    /**#@+
     * Category constants defined for test
     */
    const DATA_PROVIDER_NAME = 'category_listing_data_source';
    const PRIMARY_FIELD_NAME = 'category_id';
    const REQUEST_FIELD_NAME = 'category_id';
    const PARENT_REQUEST_FIELD_NAME = 'parent';
    const CATEGORY_ID = 1;
    const PARENT_CATEGORY_ID = 2;
    /**#@-*/

    /**
     * @var array
     */
    private $categoryData = [
        'category_id' => self::CATEGORY_ID,
        'store_ids' => [1, 2]
    ];

    /**
     * @var CategoryDataProvider
     */
    private $dataProvider;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var DataPersistorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $dataPersistorMock;

    /**
     * @var PoolInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $modifierPoolMock;

    /**
     * @var Collection
     */
    private $collectionMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */

    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $this->collectionMock = $this->getMockBuilder(Collection::class)
            ->setMethods(['getItems', 'getNewEmptyItem'])
            ->disableOriginalConstructor()
            ->getMock();
        $collectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $collectionFactoryMock->expects($this->once())
            ->method('create')
            ->will($this->returnValue($this->collectionMock));
        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);
        $this->dataPersistorMock = $this->getMockForAbstractClass(DataPersistorInterface::class);
        $this->modifierPoolMock = $this->createMock(PoolInterface::class);

        $this->modifierPoolMock->expects($this->any())
            ->method('getModifiersInstances')
            ->willReturn([]);

        $this->dataProvider = $objectManager->getObject(
            CategoryDataProvider::class,
            [
                'name' => self::DATA_PROVIDER_NAME,
                'primaryFieldName' => self::PRIMARY_FIELD_NAME,
                'requestFieldName' => self::REQUEST_FIELD_NAME,
                'collectionFactory' => $collectionFactoryMock,
                'request' => $this->requestMock,
                'dataPersistor' => $this->dataPersistorMock,
                'modifierPool' => $this->modifierPoolMock,
            ]
        );
    }

    /**
     * Testing of get data from collection
     */
    public function testGetDataFromCollection()
    {
        $categoryMock = $this->getMockBuilder(Category::class)
            ->setMethods(['getId', 'getData'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::CATEGORY_ID));
        $categoryMock->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($this->categoryData));

        $this->collectionMock->expects($this->once())
            ->method('getItems')
            ->will($this->returnValue([$categoryMock]));
        $this->requestMock->expects($this->exactly(2))
            ->method('getParam')
            ->withConsecutive(
                [$this->equalTo(self::REQUEST_FIELD_NAME)],
                [$this->equalTo(self::PARENT_REQUEST_FIELD_NAME)]
            )
            ->willReturnOnConsecutiveCalls(
                $this->returnValue(self::CATEGORY_ID),
                $this->returnValue(self::PARENT_CATEGORY_ID)
            );

        $data = $this->dataProvider->getData();
        $this->assertArrayHasKey(self::CATEGORY_ID, $data);
    }

    /**
     * Testing of get data from DataPersistor
     */
    public function testGetDataFromDataPersistor()
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with(self::REQUEST_FIELD_NAME)
            ->willReturn(self::CATEGORY_ID);

        $this->dataPersistorMock->expects($this->once())
            ->method('get')
            ->with('aw_blog_category')
            ->willReturn($this->categoryData);
        $this->dataPersistorMock->expects($this->once())
            ->method('clear')
            ->with('aw_blog_category')
            ->willReturnSelf();

        $categoryMock = $this->getMockBuilder(Category::class)
            ->setMethods(['getData', 'setData'])
            ->disableOriginalConstructor()
            ->getMock();
        $categoryMock->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($this->categoryData));
        $categoryMock->expects($this->once())
            ->method('setData')
            ->with($this->categoryData)
            ->willReturnSelf();
        $this->collectionMock->expects($this->once())
            ->method('getNewEmptyItem')
            ->will($this->returnValue($categoryMock));

        $data = $this->dataProvider->getData();
        $this->assertArrayHasKey(self::CATEGORY_ID, $data);
    }
}
