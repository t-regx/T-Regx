<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\FromArrayStream;

class FromArrayStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new FromArrayStream(['One', 'Two', 'Three']);

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['One', 'Two', 'Three'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $stream = new FromArrayStream(['One', 'Two', 'Three']);

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldGetFirstKey()
    {
        // given
        $elements = [10 => 'One', 20 => 'Two', 30 => 'Three'];
        next($elements);
        next($elements); # Intentionally move internal pointer
        $stream = new FromArrayStream($elements);

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame(10, $firstKey);
    }

    /**
     * @test
     */
    public function shouldGetFirst_assoc()
    {
        // given
        $stream = new FromArrayStream(['a' => 'One', 'b' => 'Two', 'c' => 'Three']);

        // when
        $first = $stream->first();

        // then
        $this->assertSame('One', $first);
    }

    /**
     * @test
     */
    public function shouldFirstThrow()
    {
        // given
        $stream = new FromArrayStream([]);

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldFirstReturnInteger()
    {
        // given
        $stream = new FromArrayStream([1]);

        // when
        $first = $stream->first();

        // then
        $this->assertSame(1, $first);
    }
}
