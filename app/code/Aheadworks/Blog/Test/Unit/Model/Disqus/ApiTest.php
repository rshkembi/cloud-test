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
declare(strict_types=1);

namespace Aheadworks\Blog\Test\Unit\Model\Disqus;

use Aheadworks\Blog\Model\DisqusConfig;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\HTTP\Adapter\Curl;
use Magento\Framework\HTTP\Adapter\CurlFactory;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Test for \Aheadworks\Blog\Model\Disqus\Api
 */
class ApiTest extends \PHPUnit\Framework\TestCase
{
    /**#@+
     * Constants defined for test
     */
    public const DISQUS_SECRET_KEY = 'disqus_secret_key';
    public const RESOURCE_NAME = 'disqus_api_resource';
    /**#@-*/

    /**
     * @var \Aheadworks\Blog\Model\Disqus\Api
     */
    private $disqusApiModel;

    /**
     * @var Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    private $curlMock;

    /**
     * @var Json\|\PHPUnit_Framework_MockObject_MockObject
     */
    private $serializerMock;

    /**
     * @var ProductMetadataInterface\|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productMetadataMock;

    /**
     * @var array
     */
    private $args = ['arg_name' => 'arg_value'];

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);

        $this->curlMock = $this->getMockBuilder(Curl::class)
            ->setMethods(['setConfig', 'write', 'read', 'close'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->curlMock->expects($this->any())
            ->method('write')
            ->will($this->returnValue(''));

        $curlFactoryMock = $this->getMockBuilder(CurlFactory::class)
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $curlFactoryMock->expects($this->any())
            ->method('create')
            ->will($this->returnValue($this->curlMock));

        $disqusConfigMock = $this->getMockBuilder(DisqusConfig::class)
            ->setMethods(['getSecretKey'])
            ->disableOriginalConstructor()
            ->getMock();
        $disqusConfigMock->expects($this->any())->method('getSecretKey')
            ->will($this->returnValue(self::DISQUS_SECRET_KEY));

        $this->serializerMock = $this->createPartialMock(
            Json::class,
            ['unserialize']
        );
        $this->productMetadataMock = $this->getMockForAbstractClass(ProductMetadataInterface::class);

        $this->disqusApiModel = $objectManager->getObject(
            \Aheadworks\Blog\Model\Disqus\Api::class,
            [
                'curlFactory' => $curlFactoryMock,
                'disqusConfig' => $disqusConfigMock,
                'serializer' => $this->serializerMock,
                'productMetadata' => $this->productMetadataMock
            ]
        );
    }

    /**
     * Testing that request is sent to the remote server
     */
    public function testSendRequestWrite()
    {
        $this->curlMock->expects($this->once())
            ->method('write')
            ->with(
                $this->anything(),
                $this->logicalAnd(
                    $this->stringContains(self::RESOURCE_NAME),
                    $this->stringContains('api_secret=' . self::DISQUS_SECRET_KEY),
                    $this->stringContains('arg_name=arg_value')
                )
            )
            ->willReturn('');
        $this->productMetadataMock->expects($this->once())
            ->method('getVersion')
            ->willReturn('2.4.6');
        $this->disqusApiModel->sendRequest(self::RESOURCE_NAME, $this->args);
    }

    /**
     * Testing that response is read from the server
     */
    public function testSendRequestRead()
    {
        $this->productMetadataMock->expects($this->once())
            ->method('getVersion')
            ->willReturn('2.4.6');
        $this->curlMock->expects($this->once())->method('read');
        $this->disqusApiModel->sendRequest(self::RESOURCE_NAME, $this->args);
    }

    /**
     * Testing that the connection to the server is closed
     */
    public function testSendRequestClose()
    {
        $this->productMetadataMock->expects($this->once())
            ->method('getVersion')
            ->willReturn('2.4.6');
        $this->curlMock->expects($this->once())->method('close');
        $this->disqusApiModel->sendRequest(self::RESOURCE_NAME, $this->args);
    }

    /**
     * Testing response from the server
     *
     * @dataProvider sendRequestResponseDataProvider
     */
    public function testSendRequestResponse($response, $unserializedResponse, $expected)
    {
        $this->curlMock->expects($this->any())
            ->method('read')
            ->willReturn($response);
        $this->productMetadataMock->expects($this->any())
            ->method('getVersion')
            ->willReturn('2.4.6');

        $this->serializerMock->expects($this->any())
            ->method('unserialize')
            ->with($response)
            ->willReturn($unserializedResponse);

        $this->assertEquals(
            $expected,
            $this->disqusApiModel->sendRequest(self::RESOURCE_NAME, $this->args)
        );
    }

    /**
     * Testing response from the server in the case of exception is thrown
     */
    public function testSendRequestReadException()
    {
        $this->productMetadataMock->expects($this->any())
            ->method('getVersion')
            ->willReturn('2.4.6');
        $this->curlMock->expects($this->any())
            ->method('read')
            ->willThrowException(new \Exception());
        $this->assertFalse($this->disqusApiModel->sendRequest(self::RESOURCE_NAME, $this->args));
    }

    /**
     * Data provider for testSendRequestResponse method
     *
     * @return array
     */
    public function sendRequestResponseDataProvider()
    {
        return [
            ['{"code": 0, "response":[{"fieldName": "fieldValue"}]}', ['code' => 0, 'response' => [['fieldName' => 'fieldValue']]], [['fieldName' => 'fieldValue']]],
            ['{"code": 5, "response":"Invalid API key', ['code' => 5, 'response' => 'Invalid API key'], false]
        ];
    }
}
