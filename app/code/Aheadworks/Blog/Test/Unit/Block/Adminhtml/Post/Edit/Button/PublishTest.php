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
namespace Aheadworks\Blog\Test\Unit\Block\Adminhtml\Post\Edit\Button;

use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;
use Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button\Publish as PublishButton;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button\Publish
 */
class PublishTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var string
     */
    const POST_ID = 1;

    /**
     * @var PublishButton
     */
    private $button;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var PostRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $postRepositoryMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $this->requestMock = $this->getMockForAbstractClass(RequestInterface::class);
        $this->postRepositoryMock = $this->getMockForAbstractClass(PostRepositoryInterface::class);

        $this->button = $objectManager->getObject(
            PublishButton::class,
            [
                'request' => $this->requestMock,
                'postRepository' => $this->postRepositoryMock
            ]
        );
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $this->requestMock->expects($this->any())
            ->method('getParam')
            ->with($this->equalTo('id'))
            ->will($this->returnValue(null));

        $buttonData = $this->button->getButtonData();
        $this->assertTrue(is_array($buttonData));
        $this->assertNotEmpty($buttonData);
    }
}
