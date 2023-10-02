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

use Aheadworks\Blog\Model\Post;
use Aheadworks\Blog\Model\ResourceModel\Validator\UrlKeyIsUnique;
use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Test for \Aheadworks\Blog\Model\Post
 */
class PostTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Post
     */
    private $postModel;

    /**
     * @var UrlKeyIsUnique|\PHPUnit_Framework_MockObject_MockObject
     */
    private $urlKeyIsUniqueMock;

    /**
     * Post model data
     *
     * @var array
     */
    private $postData = [
        'title' => 'Post',
        'url_key' => 'post',
        'short_content' => 'Post short content',
        'content' => 'Post content',
        'is_allow_comments' => 1,
        'meta_title' => 'post meta title',
        'meta_description' => 'post meta description',
        'store_ids' => [1, 2],
        'category_ids' => [1, 2]
    ];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->urlKeyIsUniqueMock = $this->getMockBuilder(UrlKeyIsUnique::class)
            ->setMethods(['validate'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->postModel = $objectManager->getObject(
            Post::class,
            ['urlKeyIsUnique' => $this->urlKeyIsUniqueMock]
        );
    }

    /**
     * Testing that proper exceptions are thrown if post data is incorrect
     *
     * @dataProvider validateBeforeSaveDataProvider
     * @param array $postData
     * @param string $exceptionMessage
     */
    public function testValidateBeforeSaveExceptions($postData, $exceptionMessage)
    {
        $this->urlKeyIsUniqueMock->expects($this->any())
            ->method('validate')
            ->with($this->equalTo($this->postModel))
            ->willReturn(true);
        $this->postModel->setData($postData);
        try {
            $this->postModel->validateBeforeSave();
            $this->fail();
        } catch (\Exception $e) {
            $this->assertInstanceOf(\Magento\Framework\Validator\Exception::class, $e);
            $this->assertEquals($exceptionMessage, $e->getMessage());
        }
    }

    /**
     * Testing that proper exception is thrown if post data contains duplicated URL-Key
     */
    public function testValidateBeforeSaveDuplicatedUrlKey()
    {
        $this->urlKeyIsUniqueMock->expects($this->any())
            ->method('validate')
            ->with($this->equalTo($this->postModel))
            ->willReturn(false);
        $this->postModel->setData($this->postData);

        $this->expectException(\Magento\Framework\Validator\Exception::class);
        $this->postModel->validateBeforeSave();
    }

    /**
     * Data provider for testValidateBeforeSaveExceptions method
     *
     * @return array
     */
    public function validateBeforeSaveDataProvider()
    {
        return [
            'empty title' => [
                array_merge($this->postData, ['title' => '']),
                'Title is required.'
            ],
            'empty URL-Key' => [
                array_merge($this->postData, ['url_key' => '']),
                'URL-Key is required.'
            ],
            'empty content' => [
                array_merge($this->postData, ['content' => '']),
                'Content is required.'
            ],
            'numeric URL-Key' => [
                array_merge($this->postData, ['url_key' => '123']),
                'URL-Key cannot consist only of numbers.'
            ],
            'invalid URL-Key' => [
                array_merge($this->postData, ['url_key' => 'invalid key*^']),
                'URL-Key cannot contain capital letters or disallowed symbols.'
            ],
            'empty stores' => [
                array_merge($this->postData, ['store_ids' => []]),
                'Select store view.'
            ]
        ];
    }
}
