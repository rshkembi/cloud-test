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
namespace Aheadworks\Blog\Test\Unit\Model;

use Aheadworks\Blog\Api\Data\PostInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Model\PostRegistry;

/**
 * Test for \Aheadworks\Blog\Model\PostRegistry
 */
class PostRegistryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var PostRegistry
     */
    private $postRegistry;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $this->postRegistry = $objectManager->getObject(PostRegistry::class);
    }

    /**
     * Testing of retrieve method on null
     */
    public function testRetrieveNull()
    {
        $postId = 1;

        $this->assertNull($this->postRegistry->retrieve($postId));
    }

    /**
     * Testing of retrieve method on object
     */
    public function testRetrieveObject()
    {
        $postId = 1;

        $postMock = $this->getMockForAbstractClass(PostInterface::class);
        $postMock->expects($this->once())
            ->method('getId')
            ->willReturn($postId);
        $this->postRegistry->push($postMock);
        $this->assertEquals($postMock, $this->postRegistry->retrieve($postId));
    }

    /**
     * Testing remove an instance
     */
    public function testRemove()
    {
        $postId = 1;

        $postMock = $this->getMockForAbstractClass(PostInterface::class);
        $postMock->expects($this->once())
            ->method('getId')
            ->willReturn($postId);
        $this->postRegistry->push($postMock);
        $postFromReg = $this->postRegistry->retrieve($postId);
        $this->assertEquals($postMock, $postFromReg);
        $this->postRegistry->remove($postId);
        $this->assertNull($this->postRegistry->retrieve($postId));
    }
}
