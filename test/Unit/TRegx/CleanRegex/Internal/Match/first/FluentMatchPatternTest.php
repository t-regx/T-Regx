<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoFirstElementFluentException;
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
        $result = $pattern->first();

        // then
        $this->assertEquals('foo', $result);
    }

    /**
     * @test
     */
    public function shouldInvoke_consumer()
    {
        // given
        $pattern = new FluentMatchPattern(['a' => 'foo', 'b' => 'bar'], $this->mock());

        // when
        $pattern->first(function ($value, $key = null) {
            // then
            $this->assertEquals('foo', $value);
            $this->assertNull($key); // For now, `first()` won't receive key as a second argument
        });
    }

    /**
     * @test
     */
    public function shouldThrowEmpty()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->mock());

        // then
        $this->expectException(NoFirstElementFluentException::class);

        // when
        $pattern->first();
    }

    /**
     * @test
     */
    public function shouldThrowEmpty_consumer()
    {
        // given
        $pattern = new FluentMatchPattern([], $this->mock());

        // then
        $this->expectException(NoFirstElementFluentException::class);

        // when
        $pattern->first(function () {
            $this->fail();
        });
    }

    private function mock(): NotMatchedFluentOptionalWorker
    {
        /** @var NotMatchedFluentOptionalWorker $mockObject */
        $mockObject = $this->createMock(NotMatchedFluentOptionalWorker::class);
        return $mockObject;
    }
}
