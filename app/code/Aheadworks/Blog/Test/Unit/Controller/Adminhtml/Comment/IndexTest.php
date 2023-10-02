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
namespace Aheadworks\Blog\Test\Unit\Controller\Adminhtml\Comment;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Controller\Adminhtml\Comment\Disqus\Index;
use Aheadworks\Blog\Model\DisqusCommentsService;
use Magento\Backend\Model\View\Result\RedirectFactory;
use Magento\Backend\Model\View\Result\Redirect as ResultRedirect;
use Magento\Backend\App\Action\Context;

/**
 * Test for \Aheadworks\Blog\Controller\Adminhtml\Comment\Index
 */
class IndexTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Index
     */
    private $action;

    /**
     * @var DisqusCommentsService|\PHPUnit_Framework_MockObject_MockObject
     */
    private $disqusCommentsServiceMock;

    /**
     * @var RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $resultRedirectFactoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->disqusCommentsServiceMock = $this->getMockBuilder(DisqusCommentsService::class)
            ->setMethods(['getModerateUrl'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultRedirectFactoryMock = $this->getMockBuilder(RedirectFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();

        $context = $objectManager->getObject(
            Context::class,
            [
                'resultRedirectFactory' => $this->resultRedirectFactoryMock
            ]
        );
        $this->action = $objectManager->getObject(
            Index::class,
            [
                'context' => $context,
                'disqusCommentsService' => $this->disqusCommentsServiceMock
            ]
        );
    }

    /**
     * Testing of return value of execute method
     */
    public function testExecuteResult()
    {
        $url = 'https://disqus.com/admin/moderate';

        $this->disqusCommentsServiceMock->expects($this->once())
            ->method('getModerateUrl')
            ->willReturn($url);
        $resultRedirectMock = $this->getMockBuilder(ResultRedirect::class)
            ->setMethods(['setUrl'])
            ->disableOriginalConstructor()
            ->getMock();
        $resultRedirectMock->expects($this->once())
            ->method('setUrl')
            ->with($url)
            ->willReturnSelf();
        $this->resultRedirectFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultRedirectMock);

        $this->assertSame($resultRedirectMock, $this->action->execute());
    }
}
