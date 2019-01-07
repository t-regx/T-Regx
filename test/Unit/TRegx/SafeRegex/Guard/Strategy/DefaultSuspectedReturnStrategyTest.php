<?php
namespace Test\Unit\TRegx\SafeRegex\Guard\Strategy;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\SafeRegex\Guard\Strategy\DefaultSuspectedReturnStrategy;

class DefaultSuspectedReturnStrategyTest extends TestCase
{
    /**
     * @test
     * @dataProvider correctResults
     * @param string $methodName
     * @param $result
     * @throws InternalCleanRegexException
     */
    public function shouldNotBeSuspected(string $methodName, $result)
    {
        // given
        $failureIndicators = new DefaultSuspectedReturnStrategy();

        // when
        $isSuspected = $failureIndicators->isSuspected($methodName, $result);

        // then
        $this->assertFalse($isSuspected);
    }

    public function correctResults(): array
    {
        return [
            ['preg_match', ''],
            ['preg_match_all', ''],
            ['preg_replace_callback', ''],
            ['preg_replace_callback_array', ''],
            ['preg_split', ''],
        ];
    }

    /**
     * @test
     * @dataProvider suspectedResults
     * @param string $methodName
     * @param        $result
     * @throws InternalCleanRegexException
     */
    public function shouldBeSuspected(string $methodName, $result)
    {
        // given
        $failureIndicators = new DefaultSuspectedReturnStrategy();

        // when
        $isSuspected = $failureIndicators->isSuspected($methodName, $result);

        // then
        $this->assertTrue($isSuspected);
    }

    public function suspectedResults(): array
    {
        return [
            ['preg_match', false],
            ['preg_match_all', false],
            ['preg_replace_callback', null],
            ['preg_replace_callback_array', null],
            ['preg_split', false],
        ];
    }
}
