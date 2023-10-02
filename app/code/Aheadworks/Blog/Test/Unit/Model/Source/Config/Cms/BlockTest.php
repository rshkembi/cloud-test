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
namespace Aheadworks\Blog\Test\Unit\Model\Source\Config\Cms;

use Aheadworks\Blog\Model\Source\Config\Cms\Block as CmsBlockSource;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Model\Source\Config\Cms\Block as CmsBlock;
use Magento\Cms\Model\ResourceModel\Block\Collection;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;

/**
 * Test for \Aheadworks\Blog\Model\Source\Config\Cms\Block
 */
class BlockTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CmsBlock
     */
    private $cmsBlockSourceModel;

    /**
     * @var Collection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $blockCollectionMock;

    /**
     * @var array
     */
    private $optionArray = [['option value' => 'option label']];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->blockCollectionMock = $this->getMockBuilder(Collection::class)
            ->setMethods(['toOptionArray'])
            ->disableOriginalConstructor()
            ->getMock();
        $blockCollectionFactoryMock = $this->getMockBuilder(CollectionFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $blockCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->blockCollectionMock));
        $this->cmsBlockSourceModel = $objectManager->getObject(
            CmsBlock::class,
            ['blockCollectionFactory' => $blockCollectionFactoryMock]
        );
    }

    /**
     * Testing 'toOptionArray' method call
     */
    public function testToOptionArray()
    {
        $this->blockCollectionMock->expects($this->atLeastOnce())
            ->method('toOptionArray')
            ->willReturn($this->optionArray);
        $this->assertEquals(
            array_merge(
                [CmsBlockSource::DONT_DISPLAY => 'Don\'t display'],
                $this->optionArray
            ),
            $this->cmsBlockSourceModel->toOptionArray()
        );
    }
}
