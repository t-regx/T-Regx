<?php
namespace Test\Feature\CleanRegex\stream\filter;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\Stream\ArrayStream;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Match\Stream;

/**
 * @covers \TRegx\CleanRegex\Match\Stream
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\FilterStream
 */
class StreamTest extends TestCase
{
    use TestCasePasses;

    /**
     * @test
     */
    public function shouldFilter()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity']);
        // when
        $filtered = $stream->filter(Functions::notEquals('Cypher'))->all();
        // then
        $this->assertSame(['Neo', 2 => 'Trinity'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity']);
        // when
        $filtered = $stream->filter(Functions::notEquals('Cypher'))->first();
        // then
        $this->assertSame('Neo', $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first_nextKey()
    {
        // given
        $stream = ArrayStream::of(['Cypher', 'Neo', 'Trinity']);
        // when
        $filtered = $stream->filter(Functions::notEquals('Cypher'))->first();
        // then
        $this->assertSame('Neo', $filtered);
    }

    /**
     * @test
     * @dataProvider emptyStreams
     * @param Stream $stream
     */
    public function shouldNotCall_all_forEmptyStream(Stream $stream)
    {
        // when
        $stream->filter(Functions::fail())->all();
        // then
        $this->pass();
    }

    /**
     * @test
     * @dataProvider emptyStreams
     * @param Stream $stream
     */
    public function shouldNotCall_first_forEmptyStream(Stream $stream)
    {
        // when, then
        $stream->filter(Functions::fail())->findFirst()->orElse(Functions::pass());
    }

    public function emptyStreams(): array
    {
        return [[ArrayStream::empty()], [ArrayStream::unmatched()]];
    }

    /**
     * @test
     */
    public function shouldCallFilterFirstKeysKey()
    {
        // given
        $stream = ArrayStream::of(['Foo', 'Bar', 'Door']);
        // when
        $stream->filter(Functions::pass(true))->keys()->keys()->first();
    }

    /**
     * @test
     */
    public function shouldNotInvoke_twiceForFirst()
    {
        // when
        $stream = ArrayStream::of(['Foo', 'Bar', 'Dor', 'Ver', 'Sir']);
        // when
        $stream
            ->filter(Functions::collecting($calls, Functions::equals('Sir')))
            ->first();
        // then
        $this->assertSame(['Foo', 'Bar', 'Dor', 'Ver', 'Sir'], $calls);
    }

    /**
     * @test
     * @depends shouldNotInvoke_twiceForFirst
     */
    public function shouldInvoke_untilFound()
    {
        // given
        $stream = ArrayStream::of(['one', 'two', 'three', 'four', 'five', 'six']);
        // when
        $stream
            ->filter(Functions::collecting($calls, Functions::equals('four')))
            ->first();
        // then
        $this->assertSame(['one', 'two', 'three', 'four'], $calls);
    }

    /**
     * @test
     */
    public function shouldBe_Countable()
    {
        // given
        $stream = ArrayStream::of(['Neo', 'Cypher', 'Trinity']);
        // when
        $size = count($stream->filter(Functions::notEquals('Cypher')));
        // then
        $this->assertSame(2, $size);
    }

    /**
     * @test
     */
    public function shouldFilterThousand_forFirst()
    {
        // given
        $stream = ArrayStream::of($this->prependedWithThousand(['Bar', 'Cat']));
        // when
        $first = $stream->filter(Functions::equals('Bar'))->first();
        // then
        $this->assertSame('Bar', $first);
    }

    /**
     * @test
     */
    public function shouldFilterThousand_forFirstKey()
    {
        // given
        $stream = ArrayStream::of($this->prependedWithThousand(['Bar' => 'Bar', 'Cat' => 'Cat']));
        // when
        $first = $stream->filter(Functions::equals('Bar'))->keys()->first();
        // then
        $this->assertSame('Bar', $first);
    }

    private function prependedWithThousand(array $arguments): array
    {
        return \array_merge(\array_fill(0, 1000, 'Invisible'), $arguments);
    }
}
