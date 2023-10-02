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
namespace Aheadworks\Blog\Test\Unit\Model\Sitemap;

use Aheadworks\Blog\Model\Sitemap\ItemsProviderComposite;
use Magento\Framework\DataObject;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Aheadworks\Blog\Model\Sitemap\ItemsProvider\ProviderInterface;
use Magento\Framework\App\ProductMetadataInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ItemsProviderCompositeTest
 * @package Aheadworks\Blog\Test\Unit\Model\Sitemap
 */
class ItemsProviderCompositeTest extends TestCase
{
    /**
     * @var ProductMetadataInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productMetadataMock;

    /**
     * @var LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $loggerMock;

    /**
     * @var ProviderInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $providerMock;

    /**
     * @var ItemsProviderComposite
     */
    private $compositeProvider;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->productMetadataMock = $this->createMock(ProductMetadataInterface::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);
        $this->providerMock = $this->createMock(ProviderInterface::class);
        $this->compositeProvider = $objectManager->getObject(
            ItemsProviderComposite::class,
            [
                'productMetadata' => $this->productMetadataMock,
                'logger' => $this->loggerMock,
                'providers' => [
                    $this->providerMock,
                    $this->createMock(DataObject::class),
                    'Sample\Class\Name'
                ]
            ]
        );
    }

    /**
     * Test getItems method
     *
     * @param bool $is23x
     * @dataProvider getItemsProvider
     */
    public function testGetItems($is23x)
    {
        $method = $is23x ? 'getItems23x' : 'getItems';
        $version = $is23x ? '2.3.0' : '2.2.7';
        $storeId = 1;
        $items = [$this->createMock(DataObject::class)];

        $this->productMetadataMock->expects($this->once())
            ->method('getVersion')
            ->willReturn($version);
        $this->providerMock->expects($this->atLeastOnce())
            ->method($method)
            ->with($storeId)
            ->willReturn($items);
        $this->loggerMock->expects($this->any())
            ->method('warning');

        $this->assertEquals($items, $this->compositeProvider->getItems($storeId));
    }

    /**
     * @return array
     */
    public function getItemsProvider()
    {
        return [[true], [false]];
    }
}
