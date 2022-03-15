<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Stream\Base;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ConstantAllBase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ConstantFirstBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase
 */
class StreamBaseTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $stream = new StreamBase(new ConstantAllBase($this->matchesOffset()));

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
        $stream = new StreamBase(new ConstantFirstBase($this->matchOffset()));

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
        $stream = new StreamBase(new ConstantFirstBase(new RawMatchOffset([])));

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
        $stream = new StreamBase(new ConstantFirstBase(new RawMatchOffset([])));

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
        $stream = new StreamBase(new ConstantAllBase(new RawMatchesOffset([[]])));

        // then
        $this->expectException(UnmatchedStreamException::class);

        // when
        $stream->all();
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
        return new RawMatchOffset([['Joffrey', 1]]);
    }
}
