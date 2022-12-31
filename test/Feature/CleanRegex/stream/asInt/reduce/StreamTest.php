<?php
namespace Test\Feature\CleanRegex\stream\asInt\reduce;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;

/**
 * @coversNothing
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldSum()
    {
        // given
        $stream = ArrayStream::of(['14', '15', '16']);
        // when
        $reduced = $stream->asInt()->reduce(Functions::sum(), 2);
        // then
        $this->assertSame(47, $reduced);
        $this->assertSame(14 + 15 + 16 + 2, $reduced);
    }
}
