<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\findNth;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\CustomException;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFindSecond()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => 'foo', 'b' => 'bar'], $this->worker());

        // when + then
        $this->assertEquals('bar', $pattern->findNth(1)->orReturn('missing'));
        $this->assertEquals('bar', $pattern->findNth(1)->orElse('strtolower'));
        $this->assertEquals('bar', $pattern->findNth(1)->orThrow());
    }

    /**
     * @test
     */
    public function shouldFindFirst_throwEmpty()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage("Expected to get the #0 element from fluent pattern, but the elements feed has 0 elements.");

        // when
        $pattern->findNth(0)->orThrow();
    }

    /**
     * @test
     */
    public function shouldFindFirst_orReturn()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // when
        $result = $pattern->findNth(0)->orReturn('otherValue');

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
        $result = $pattern->findNth(0)->orElse(function () {
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
        $pattern->findNth(0)->orElse(function () {
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
        $this->expectException(CustomException::class);
        $this->expectExceptionMessage("Expected to get the #0 element from fluent pattern, but the elements feed has 0 elements.");

        // when
        $pattern->findNth(0)->orThrow(CustomException::class);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // given
        $pattern = new FluentMatchPattern([null], $this->worker());

        // when
        $result = $pattern->findNth(0)->orThrow();

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeIndex()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->worker());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative index: -2');

        // when
        $pattern->nth(-2);
    }

    private function worker(): NotMatchedFluentOptionalWorker
    {
        return new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), 'foo bar');
    }
}
