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
 * @package    BlogSearch
 * @version    1.1.3
 * @copyright  Copyright (c) 2023 Aheadworks Inc. (https://aheadworks.com/)
 * @license    https://aheadworks.com/end-user-license-agreement/
 */
namespace Aheadworks\Pquestion\Test\Unit\Model\Question;

use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;
use Magento\Framework\Search\EngineResolverInterface;
use Aheadworks\BlogSearch\Model\SearchAllowedChecker;

/**
 * Class ResolverTest
 * @package Aheadworks\Pquestion\Test\Unit\Model\Question
 */
class SearchAllowedCheckerTest extends TestCase
{
    /**
     * @var EngineResolverInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $engineResolverMock;

    /**
     * @var SearchAllowedChecker
     */
    private $searchAllowedChecker;

    /**
     * Init mocks for tests
     *
     * @return void
     */
    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);

        $this->engineResolverMock = $this->getMockForAbstractClass(
            EngineResolverInterface::class
        );

        $this->searchAllowedChecker = $objectManager->getObject(
            SearchAllowedChecker::class,
            [
                'engineResolver' => $this->engineResolverMock,
            ]
        );
    }

    /**
     * Test execute method
     *
     * @param string $engine
     * @param bool $result
     * @dataProvider getEngineResolverDataProvider
     */
    public function testExecute($engine, $resultForEngine)
    {
        $this->engineResolverMock
            ->expects(self::once())
            ->method('getCurrentSearchEngine')
            ->willReturn($engine);

        self::assertSame($this->searchAllowedChecker->execute(), $resultForEngine);
    }

    /**
     * @return array
     */
    public function getEngineResolverDataProvider()
    {
        return [
            [
                'engine' => SearchAllowedChecker::ALLOWED_ENGINES[0],
                'resultForEngine' => true
            ],
            [
                'engine' => 'someNotAllowedEngine',
                'resultForEngine' => false
            ],
        ];
    }
}
