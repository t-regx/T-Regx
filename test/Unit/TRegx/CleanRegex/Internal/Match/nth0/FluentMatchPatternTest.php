<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\nth0;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class FluentMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => 'foo', 'b' => 'bar', 6 => 'lorem', 7 => 'ipsum'], $this->mock());

        // when
        $result = $pattern->nth(0);

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldReturnNull()
    {
        // given
        $pattern = new FluentMatchPattern([null], $this->mock());

        // when
        $result = $pattern->nth(0);

        // then
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function shouldGetThird()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => 'foo', 'b' => 'bar', 6 => 'lorem', 7 => 'ipsum'], $this->mock());

        // when
        $result = $pattern->nth(2);

        // then
        $this->assertEquals('lorem', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_onNegativeIndex()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->mock());

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Negative index: -2');

        // when
        $pattern->nth(-2);
    }

    /**
     * @test
     */
    public function shouldThrow_onTooHigher()
    {
        // given
        $pattern = new FluentMatchPattern(['foo'], $this->mock());

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the #2 element from fluent pattern, but the elements feed has 1 elements.');

        // when
        $pattern->nth(2);
    }

    private function mock(): NotMatchedFluentOptionalWorker
    {
        return new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), 'foo bar');
    }
}
