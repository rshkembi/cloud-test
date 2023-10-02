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

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button\Update;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Blog\Api\Data\PostInterface;
use Aheadworks\Blog\Api\PostRepositoryInterface;

/**
 * Test for \Aheadworks\Blog\Block\Adminhtml\Post\Edit\Button\Update
 */
class UpdateTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var int
     */
    const POST_ID = 1;

    /**
     * @var Update
     */
    private $button;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $requestMock = $this->getMockForAbstractClass(RequestInterface::class);
        $requestMock->expects($this->any())
            ->method('getParam')
            ->with($this->equalTo('id'))
            ->will($this->returnValue(self::POST_ID));

        $postMock = $this->getMockForAbstractClass(PostInterface::class);
        $postRepositoryMock = $this->getMockForAbstractClass(PostRepositoryInterface::class);
        $postRepositoryMock->expects($this->any())
            ->method('get')
            ->with($this->equalTo(self::POST_ID))
            ->will($this->returnValue($postMock));

        $this->button = $objectManager->getObject(
            Update::class,
            [
                'request' => $requestMock,
                'postRepository' => $postRepositoryMock
            ]
        );
    }

    /**
     * Testing of return value of getButtonData method
     */
    public function testGetButtonData()
    {
        $this->assertTrue(is_array($this->button->getButtonData()));
    }
}
