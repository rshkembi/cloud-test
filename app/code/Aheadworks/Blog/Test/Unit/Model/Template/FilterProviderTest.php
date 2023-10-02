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
namespace Aheadworks\Blog\Test\Unit\Model\Template;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Cms\Model\Template\Filter;
use Aheadworks\Blog\Model\Template\FilterProvider;
use Magento\Framework\ObjectManagerInterface;

/**
 * Test for \Aheadworks\Blog\Model\Template\FilterProvider
 */
class FilterProviderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    const FILTER_CLASS_NAME = Filter::class;

    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * @var Filter|\PHPUnit_Framework_MockObject_MockObject
     */
    private $filter;

    /**
     * @var ObjectManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $objectManager;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->filter = $this->getMockBuilder(self::FILTER_CLASS_NAME)
            ->disableOriginalConstructor()
            ->getMock();
        $this->objectManager = $this->getMockBuilder(ObjectManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->filterProvider = $objectManager->getObject(
            FilterProvider::class,
            [
                'objectManager' => $this->objectManager,
                'filterClassName' => self::FILTER_CLASS_NAME
            ]
        );
    }

    /**
     * Testing that filter instance is retrieved correctly
     */
    public function testGetFilterRetrieve()
    {
        $this->objectManager->expects($this->atLeastOnce())
            ->method('get')
            ->with($this->equalTo(self::FILTER_CLASS_NAME))
            ->will($this->returnValue($this->filter));
        $this->filterProvider->getFilter();
    }

    /**
     * Testing that filter instance is cached
     */
    public function testGetFilterCaching()
    {
        $this->objectManager->expects($this->once())
            ->method('get')
            ->with($this->equalTo(self::FILTER_CLASS_NAME))
            ->will($this->returnValue($this->filter));
        $this->filterProvider->getFilter();
        $this->filterProvider->getFilter();
    }

    /**
     * Testing return value of 'getFilter' method
     */
    public function testGetFilterResult()
    {
        $this->objectManager->expects($this->any())
            ->method('get')
            ->with($this->equalTo(self::FILTER_CLASS_NAME))
            ->will($this->returnValue($this->filter));
        $this->assertSame($this->filter, $this->filterProvider->getFilter());
    }

    /**
     * Testing that proper exception is thrown in the case of wrong filter instance
     */
    public function testGetFilterWrongInstance()
    {
        $wrongFilterClassName = 'WrongClass';
        $wrongFilterMock = $this->getMockBuilder($wrongFilterClassName)
            ->getMock();
        $this->objectManager->expects($this->any())
            ->method('get')
            ->with($this->equalTo($wrongFilterClassName))
            ->will($this->returnValue($wrongFilterMock));
        $objectManager = new ObjectManager($this);
        $filterProvider = $objectManager->getObject(
            FilterProvider::class,
            [
                'objectManager' => $this->objectManager,
                'filterClassName' => $wrongFilterClassName
            ]
        );
        $this->expectException(\Exception::class);
        $filterProvider->getFilter();
    }
}
