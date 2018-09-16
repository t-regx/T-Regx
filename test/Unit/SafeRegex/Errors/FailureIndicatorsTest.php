<?php
namespace Test\Unit\SafeRegex\Errors;

use CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use PHPUnit\Framework\TestCase;
use SafeRegex\Errors\FailureIndicators;

class FailureIndicatorsTest extends TestCase
{
    /**
     * @test
     * @dataProvider vagueMethods
     * @param string $methodName
     */
    public function shouldNotBeSuspectedForVagueMethods(string $methodName)
    {
        // given
        $failureIndicators = new FailureIndicators();
        $anyValue = null;

        // when
        $isSuspected = $failureIndicators->suspected($methodName, $anyValue);

        // then
        $this->assertFalse($isSuspected);
    }

    function vagueMethods()
    {
        return [
            ['preg_quote'],
            ['preg_grep']
        ];
    }

    /**
     * @test
     * @dataProvider methods
     * @param string $methodName
     * @param        $result
     */
    public function shouldResultBeSuspected(string $methodName, $result)
    {
        // given
        $failureIndicators = new FailureIndicators();

        // when
        $isSuspected = $failureIndicators->suspected($methodName, $result);

        // then
        $this->assertTrue($isSuspected);
    }

    function methods()
    {
        return [
            ['preg_match', false],
            ['preg_match_all', false],
            ['preg_replace', null],
            ['preg_filter', null],
            ['preg_replace_callback', null],
            ['preg_replace_callback_array', null],
            ['preg_split', false],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForUnexpectedMethod()
    {
        // given
        $failureIndicators = new FailureIndicators();
        $anyValue = null;
        $unexpectedMethod = 'asd';

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $failureIndicators->suspected($unexpectedMethod, $anyValue);
    }
}
