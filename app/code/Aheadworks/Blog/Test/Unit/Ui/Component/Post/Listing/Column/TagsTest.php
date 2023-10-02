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
namespace Aheadworks\Blog\Test\Unit\Ui\Component\Post\Listing\Column;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Ui\Component\Post\Listing\Column\Tags;
use Magento\Framework\View\Element\UiComponent\Processor;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Test for \Aheadworks\Blog\Ui\Component\Post\Listing\Column\Tags
 */
class TagsTest extends \PHPUnit\Framework\TestCase
{
    /**#@+
     * Tags constants defined for test
     */
    const TAG1_NAME = 'tag 1';
    const TAG2_NAME = 'tag 2';
    /**#@-*/

    /**
     * @var Tags
     */
    private $column;

    /**
     * @var array
     */
    private $post = [
        'tag_names' => [self::TAG1_NAME, self::TAG2_NAME]
    ];

    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $processorMock = $this->getMockBuilder(Processor::class)
            ->setMethods([])
            ->disableOriginalConstructor()
            ->getMock();
        $processorMock->expects($this->any())
            ->method('register');
        $contextMock = $this->getMockBuilder(ContextInterface::class)
            ->getMockForAbstractClass();
        $contextMock->expects($this->any())
            ->method('getProcessor')
            ->willReturn($processorMock);

        $this->column = $objectManager->getObject(
            Tags::class,
            ['context' => $contextMock]
        );
    }

    /**
     * Testing of prepareDataSource method
     */
    public function testPrepareDataSource()
    {
        $dataSource = ['data' => ['items' => [$this->post]]];
        $dataSourcePrepared = $this->column->prepareDataSource($dataSource);
        $this->assertEquals(
            self::TAG1_NAME . ', ' . self::TAG2_NAME,
            $dataSourcePrepared['data']['items'][0]['tags']
        );
    }
}
