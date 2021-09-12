<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Impl\AllStream;
use Test\Utils\Impl\FirstKeyStream;
use Test\Utils\Impl\FirstStream;
use Test\Utils\Impl\ThrowStream;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\MapStream
 */
class MapStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturn_all()
    {
        // given
        $stream = new MapStream(new AllStream(['One', 'Two', 'Three']), 'strToUpper');

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['ONE', 'TWO', 'THREE'], $all);
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new MapStream(new FirstStream('One'), function (string $element) {
            $this->assertSame('One', $element, 'Failed to assert that callback is only called for the first element');
            return 'foo';
        });

        // when
        $first = $stream->first();

        // then
        $this->assertSame('foo', $first);
    }

    /**
     * @test
     */
    public function shouldReturn_firstKey_dataTypeString()
    {
        // given
        $stream = new MapStream(new FirstKeyStream('foo'), Functions::fail());

        // when
        $firstKey = $stream->firstKey();

        // then
        $this->assertSame('foo', $firstKey);
    }

    /**
     * @test
     */
    public function shouldReturn_first_dataTypeInteger()
    {
        // given
        $stream = new MapStream(new FirstStream(1), Functions::identity());

        // when
        $first = $stream->first();

        // then
        $this->assertSame(1, $first);
    }

    /**
     * @test
     */
    public function shouldRethrow_first()
    {
        // given
        $stream = new MapStream(new ThrowStream(new NoFirstStreamException()), 'strToUpper');

        // then
        $this->expectException(NoFirstStreamException::class);

        // when
        $stream->first();
    }

    private function mock(string $methodName, string $setter, $value): Upstream
    {
        /** @var Upstream|MockObject $stream */
        $stream = $this->createMock(Upstream::class);
        $stream->expects($this->once())->method($methodName)->$setter($value);
        $stream->expects($this->never())->method($this->logicalNot($this->matches($methodName)));
        return $stream;
    }
}
