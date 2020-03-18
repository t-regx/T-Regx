<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\CustomSubjectException;
use TRegx\CleanRegex\Exception\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->worker());

        // when
        $result1 = $pattern->findFirst('strtoupper')->orReturn('');
        $result2 = $pattern->findFirst('strtoupper')->orElse('strtolower');
        $result3 = $pattern->findFirst('strtoupper')->orThrow();

        // then
        $this->assertEquals('FOO', $result1);
        $this->assertEquals('FOO', $result2);
        $this->assertEquals('FOO', $result3);
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(NoFirstElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->findFirst('strtoupper')->orThrow();
    }

    /**
     * @test
     */
    public function shouldFindFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $result = $pattern->findFirst('strtoupper')->orReturn('otherValue');

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $result = $pattern->findFirst('strtoupper')->orElse(function () {
            return 'otherValue';
        });

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldFindFirst_orElse_notPassArguments()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $pattern->findFirst('strtoupper')->orElse(function () {
            // when
            $arguments = func_get_args();

            // then
            $this->assertEmpty($arguments);
        });
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty_custom()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(CustomSubjectException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->findFirst('strtoupper')->orThrow(CustomSubjectException::class);
    }

    private function worker(): NotMatchedFluentOptionalWorker
    {
        return new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), 'foo bar');
    }
}
