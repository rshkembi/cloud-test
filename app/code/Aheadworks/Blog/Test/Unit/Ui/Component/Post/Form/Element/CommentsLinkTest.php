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

use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\Source\Config\Comments\Service;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Ui\Component\Post\Form\Element\CommentsLink;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\View\Element\UiComponent\Processor;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Aheadworks\Blog\Model\Url\Builder\Comment\UrlBuilder;

/**
 * Test for \Aheadworks\Blog\Ui\Component\Post\Form\Element\CommentsLink
 */
class CommentsLinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    const DISQUS_ADMIN_URL = 'https://forum_code.disqus.com/admin/';

    /**
     * @var CommentsLink
     */
    private $commentsLink;

    /**
     * @var Session|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sessionMock;

    /**
     * @var UrlBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $commentBuilderMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var Config
     */
    private $config;

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

        $this->commentBuilderMock = $this->getMockBuilder(UrlBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->config = $objectManager->getObject(Config::class);

        $this->sessionMock = $this->getMockBuilder(Session::class)
            ->setMethods(['isAllowed'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->commentsLink = $objectManager->getObject(
            CommentsLink::class,
            [
                'context' => $contextMock,
                'commentUrlBuilder' =>  $this->commentBuilderMock,
                'config' => $this->configMock,
                'authSession' => $this->sessionMock,
                'data' => ['config' => []]
            ]
        );
    }

    /**
     * Testing of prepare component configuration when comments management is allowed
     */
    public function testPrepareCommentsAllowed()
    {
        $this->configMock->expects($this->once())
            ->method('getCommentType')
            ->willReturn(Service::DISQUS);
        $this->sessionMock->expects($this->once())
            ->method('isAllowed')
            ->with($this->equalTo('Aheadworks_Blog::comments_disqus'))
            ->willReturn(true);
        $this->commentBuilderMock->expects($this->once())
            ->method('getCommentUrl')
            ->willReturn(self::DISQUS_ADMIN_URL);
        $this->commentsLink->prepare();
        $config = $this->commentsLink->getData('config');
        $this->assertArrayHasKey('url', $config);
        $this->assertArrayHasKey('linkLabel', $config);
        $this->assertEquals(self::DISQUS_ADMIN_URL, $config['url']);
    }

    /**
     * Testing of prepare component configuration when comments management is not allowed
     */
    public function testPrepareCommentsIsNotAllowed()
    {
        $this->configMock->expects($this->once())
            ->method('getCommentType')
            ->willReturn(Service::DISQUS);
        $this->sessionMock->expects($this->once())
            ->method('isAllowed')
            ->with($this->equalTo('Aheadworks_Blog::comments_disqus'))
            ->willReturn(false);
        $this->commentsLink->prepare();
        $config = $this->commentsLink->getData('config');
        $this->assertArrayNotHasKey('url', $config);
        $this->assertArrayNotHasKey('linkLabel', $config);
    }
}
