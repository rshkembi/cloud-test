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
namespace Aheadworks\Blog\Test\Unit\Controller\Adminhtml\Post;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Controller\Adminhtml\Post\NewAction;
use Magento\Framework\Controller\Result\Forward;
use Magento\Backend\Model\View\Result\ForwardFactory;

/**
 * Test for \Aheadworks\Blog\Controller\Adminhtml\Post\NewAction
 */
class NewTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var NewAction
     */
    private $action;

    /**
     * @var Forward|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultForwardMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $this->resultForwardMock = $this->getMockBuilder(Forward::class)
            ->setMethods(['forward'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultForwardMock->expects($this->any())
            ->method('forward')
            ->will($this->returnSelf());
        $resultForwardFactoryMock = $this->getMockBuilder(ForwardFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $resultForwardFactoryMock->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->resultForwardMock));

        $this->action = $objectManager->getObject(
            NewAction::class,
            ['resultForwardFactory' => $resultForwardFactoryMock]
        );
    }

    /**
     * Testing of return value of execute method
     */
    public function testExecuteResult()
    {
        $this->assertSame($this->resultForwardMock, $this->action->execute());
    }
}
