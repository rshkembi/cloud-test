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
namespace Aheadworks\Blog\Test\Unit\Model\Validator;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Aheadworks\Blog\Model\Validator\UrlKey;

/**
 * Test for \Aheadworks\Blog\Model\Validator\UrlKey
 */
class UrlKeyTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UrlKey
     */
    private $validator;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    public function setUp() : void
    {
        $objectManager = new ObjectManager($this);
        $this->validator = $objectManager->getObject(UrlKey::class);
    }

    /**
     * Testing of validation
     *
     * @dataProvider isValidDataProvider
     */
    public function testIsValid($value, $expectedResult, $expectedMessages)
    {
        $result = $this->validator->isValid($value);
        $this->assertEquals($expectedResult, $result);
        $this->assertEquals($expectedMessages, array_values($this->validator->getMessages()));
    }

    /**
     * Data provider for testIsValid method
     *
     * @return array
     */
    public function isValidDataProvider()
    {
        return [
            'valid' => ['key', true, []],
            'empty' => ['', false, ['Value is required and can\'t be empty']],
            'numeric' => ['123', false, ['Value consists of numbers']],
            'disallowed symbols' => ['invalid key*^', false, ['Value contains disallowed symbols']],
            'invalid type' => [1, false, ['Invalid type given. String expected']]
        ];
    }
}
