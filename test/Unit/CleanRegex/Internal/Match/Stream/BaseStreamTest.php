<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\BaseStream
 */
class BaseStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new BaseStream($this->baseAll());

        // when
        $all = $stream->all();

        // then
        $this->assertSame(['Joffrey', 'Cersei', 'Ilyn Payne', 'The Hound'], $all->getTexts());
    }

    /**
     * @test
     */
    public function shouldReturn_first()
    {
        // given
        $stream = new BaseStream($this->baseFirst());

        // when
        $first = $stream->first();

        // then
        $this->assertSame('Joffrey', $first->getText());
    }

    /**
     * @test
     */
    public function shouldThrow_first_forUnmatched()
    {
        // given
        $stream = new BaseStream($this->baseFirstUnmatched());

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $stream->first();
    }

    /**
     * @test
     */
    public function shouldThrow_firstKey_forUnmatched()
    {
        // given
        $stream = new BaseStream($this->baseFirstUnmatched());

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $stream->firstKey();
    }

    /**
     * @test
     */
    public function shouldAll_returnEmpty_unmatched()
    {
        // given
        $stream = new BaseStream($this->baseAllUnmatched());

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $stream->all();
    }

    private function baseAllUnmatched(): Base
    {
        return $this->baseAllWith(new RawMatchesOffset([[]]));
    }

    private function baseAll(): Base
    {
        return $this->baseAllWith($this->matchesOffset());
    }

    private function baseAllWith(RawMatchesOffset $matches): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn($matches);
        $base->expects($this->never())->method($this->logicalNot($this->matches('matchAllOffsets')));
        return $base;
    }

    private function baseFirst(): Base
    {
        return $this->baseFirstWith($this->matchOffset());
    }

    private function baseFirstUnmatched(): Base
    {
        return $this->baseFirstWith(new RawMatchOffset([], null));
    }

    private function baseFirstWith(IRawMatchOffset $match): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchOffset')->willReturn($match);
        $base->expects($this->never())->method($this->logicalNot($this->matches('matchOffset')));
        return $base;
    }

    private function matchesOffset(): RawMatchesOffset
    {
        return new RawMatchesOffset([[
            ['Joffrey', 1],
            ['Cersei', 2],
            ['Ilyn Payne', 3],
            ['The Hound', 4],
        ]]);
    }

    private function matchOffset(): RawMatchOffset
    {
        return new RawMatchOffset([['Joffrey', 1]], 0);
    }
}
