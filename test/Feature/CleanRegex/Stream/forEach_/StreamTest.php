<?php
namespace Test\Feature\CleanRegex\Stream\forEach_;

use PHPUnit\Framework\TestCase;
use Test\Utils\ArrayStream;
use Test\Utils\Functions;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\StreamTerminal
 */
class StreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldIterate()
    {
        // given
        $stream = ArrayStream::of(['One', 'Two', 'Three']);
        // when
        $stream->forEach(Functions::collect($result));
        // then
        $this->assertSame(['One', 'Two', 'Three'], $result);
    }

    /**
     * @test
     */
    public function shouldForEach_acceptKey()
    {
        // given
        $stream = ArrayStream::of(['Foo' => '9', 2 => 'Bar']);
        // when
        $stream->forEach(Functions::collectEntries($arguments));
        // then
        $arr = [
            ['9', 'Foo'],
            ['Bar', 2]
        ];
        $this->assertSame($arr, $arguments);
    }
}
