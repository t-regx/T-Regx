<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\forFirst;

use PHPUnit\Framework\TestCase;
use Test\Feature\TRegx\CleanRegex\Replace\by\group\CustomException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Exception\CleanRegex\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldForFirst()
    {
        // given
        $pattern = new FluentMatchPattern(['foo', 'bar'], $this->worker());

        // when
        $result1 = $pattern->forFirst('strtoupper')->orReturn('');
        $result2 = $pattern->forFirst('strtoupper')->orElse('strtolower');
        $result3 = $pattern->forFirst('strtoupper')->orThrow();

        // then
        $this->assertEquals('FOO', $result1);
        $this->assertEquals('FOO', $result2);
        $this->assertEquals('FOO', $result3);
    }

    /**
     * @test
     */
    public function shouldForFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(NoFirstElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->forFirst('strtoupper')->orThrow();
    }

    /**
     * @test
     */
    public function shouldForFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $result = $pattern->forFirst('strtoupper')->orReturn('otherValue');

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldForFirst_orElse()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $result = $pattern->forFirst('strtoupper')->orElse(function () {
            return 'otherValue';
        });

        // then
        $this->assertEquals('otherValue', $result);
    }

    /**
     * @test
     */
    public function shouldForFirst_orElse_notPassArguments()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $pattern->forFirst('strtoupper')->orElse(function () {
            // when
            $arguments = func_get_args();

            // then
            $this->assertEmpty($arguments);
        });
    }

    /**
     * @test
     */
    public function shouldForFirst_throwEmpty_custom()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get the first element from fluent pattern, but the elements feed is empty");

        // when
        $pattern->forFirst('strtoupper')->orThrow(CustomException::class);
    }

    private function worker(): NotMatchedWorker
    {
        return new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), new Subject('asd'));
    }
}
