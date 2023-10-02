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
namespace Aheadworks\Blog\Test\Unit\Ui\Component\Post\Form\Element;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Ui\Component\Post\Form\Element\StatusLabel;
use Magento\Framework\View\Element\UiComponent\Processor;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Aheadworks\Blog\Model\Source\Post\Status;

/**
 * Test for \Aheadworks\Blog\Ui\Component\Post\Form\Element\StatusLabel
 */
class StatusLabelTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var StatusLabel
     */
    private $statusLabel;

    /**
     * @var array
     */
    private $sourceArray = ['optionName' => 'optionValue'];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $processorMock = $this->getMockBuilder(Processor::class)
            ->setMethods(['register'])
            ->disableOriginalConstructor()
            ->getMock();
        $contextMock = $this->getMockForAbstractClass(ContextInterface::class);
        $contextMock->expects($this->exactly(2))
            ->method('getProcessor')
            ->will($this->returnValue($processorMock));

        $statusSourceMock = $this->getMockBuilder(Status::class)
            ->setMethods(['getOptionsForPostForm'])
            ->disableOriginalConstructor()
            ->getMock();
        $statusSourceMock->expects($this->any())
            ->method('getOptionsForPostForm')
            ->will($this->returnValue($this->sourceArray));

        $this->statusLabel = $objectManager->getObject(
            StatusLabel::class,
            [
                'context' => $contextMock,
                'statusSource' => $statusSourceMock,
                'data' => ['config' => []]
            ]
        );
    }

    /**
     * Testing of prepare component configuration
     */
    public function testPrepare()
    {
        $this->statusLabel->prepare();
        $config = $this->statusLabel->getData('config');
        $this->assertArrayHasKey('statusOptions', $config);
        $this->assertEquals($this->sourceArray, $config['statusOptions']);
    }
}
