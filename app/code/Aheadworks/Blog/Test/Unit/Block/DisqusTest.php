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
namespace Aheadworks\Blog\Test\Unit\Block;

use Aheadworks\Blog\Block\Post\Comment\Disqus;
use Aheadworks\Blog\Model\Config;
use Aheadworks\Blog\Model\DisqusConfig;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Blog\Block\Disqus
 */
class DisqusTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Disqus
     */
    private $block;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->configMock = $this->getMockBuilder(Config::class)
            ->setMethods(['isCommentsEnabled'])
            ->disableOriginalConstructor()
            ->getMock();
        $disqusConfigMock = $this->getMockBuilder(DisqusConfig::class)
            ->setMethods(['getForumCode'])
            ->disableOriginalConstructor()
            ->getMock();
        $disqusConfigMock->expects($this->any())
            ->method('getForumCode')
            ->will($this->returnValue('forum_code'));

        $this->block = $objectManager->getObject(
            Disqus::class,
            ['config' => $this->configMock]
        );
    }

    /**
     * Testing of isCommentsEnabled method
     */
    public function testIsCommentsEnabled()
    {
        $isCommentsEnabled = true;
        $this->configMock->expects($this->any())
            ->method('isCommentsEnabled')
            ->will($this->returnValue($isCommentsEnabled));
        $this->assertEquals($isCommentsEnabled, $this->block->isCommentsEnabled());
    }

    /**
     * Testing of retrieving of count script url
     */
    public function testGetCountScriptUrl()
    {
        $this->assertTrue(is_string($this->block->getCountScriptUrl()));
    }

    /**
     * Testing of retrieving of embed script url
     */
    public function testGetEmbedScriptUrl()
    {
        $this->assertTrue(is_string($this->block->getEmbedScriptUrl()));
    }
}
