<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match\Switcher;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Switcher\BaseStream;
use TRegx\CleanRegex\Internal\Model\Match\IRawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class BaseStreamTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = new BaseStream($this->baseAll());

        // when
        $all = $switcher->all();

        // then
        $this->assertEquals(['Joffrey', 'Cersei', 'Ilyn Payne', 'The Hound'], $all->getTexts());
    }

    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $switcher = new BaseStream($this->baseFirst());

        // when
        $first = $switcher->first();

        // then
        $this->assertEquals('Joffrey', $first->getText());
    }

    /**
     * @test
     */
    public function shouldFirstThrow_unmatched()
    {
        // given
        $switcher = new BaseStream($this->baseFirstUnmatched());

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldAll_returnEmpty_unmatched()
    {
        // given
        $switcher = new BaseStream($this->baseAllUnmatched());

        // when
        $all = $switcher->all();

        // then
        $this->assertFalse($all->matched());
        $this->assertEquals([], $all->getTexts());
    }

    /**
     * @test
     */
    public function shouldFirstThrow_afterAll_unmatched()
    {
        // given
        $switcher = new BaseStream($this->baseAllUnmatched());

        // then
        $this->expectException(NoFirstSwitcherException::class);

        // when
        $switcher->all();
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldAll_callBase_once()
    {
        // given
        $switcher = new BaseStream($this->baseAll());

        // when
        $switcher->all();
        $switcher->all();
        $switcher->all();
    }

    /**
     * @test
     */
    public function shouldRaw_callBase_once()
    {
        // given
        $switcher = new BaseStream($this->baseAll());

        // when
        $switcher->getRawMatches();
        $switcher->getRawMatches();
        $switcher->getRawMatches();
    }

    /**
     * @test
     */
    public function shouldFirst_callBase_once()
    {
        // given
        $switcher = new BaseStream($this->baseFirst());

        // when
        $switcher->first();
        $switcher->first();
        $switcher->first();
    }

    /**
     * @test
     */
    public function shouldCallAll_afterFirst()
    {
        // given
        $switcher = new BaseStream($this->baseBoth());

        // when
        $switcher->first();
        $switcher->all();
    }

    /**
     * @test
     */
    public function shouldNotCallFirst_afterAll()
    {
        // given
        $switcher = new BaseStream($this->baseAll());

        // when
        $switcher->all();
        $matchOffset = $switcher->first();

        // then
        $this->assertEquals('Joffrey', $matchOffset->getText());
    }

    /**
     * @test
     */
    public function shouldNotCallFirst_afterAllFirst()
    {
        // given
        $switcher = new BaseStream($this->baseBoth());

        // when
        $switcher->first();
        $switcher->all();
        $matchOffset = $switcher->first();

        // then
        $this->assertEquals('Joffrey', $matchOffset->getText());
    }

    /**
     * @test
     */
    public function shouldFirstKey_beAlwaysZero()
    {
        // given
        $switcher = new BaseStream($this->zeroInteraction());

        // when
        $firstKey = $switcher->firstKey();

        // then
        $this->assertSame(0, $firstKey);
    }

    private function baseBoth(): Base
    {
        return $this->baseBothWith($this->matchesOffset(), $this->matchOffset());
    }

    private function baseBothWith(IRawMatchesOffset $matches, IRawMatchOffset $match): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn($matches);
        $base->expects($this->once())->method('matchOffset')->willReturn($match);
        $base->expects($this->never())->method($this->logicalNot($this->logicalOr(
            $this->matches('matchAllOffsets'),
            $this->matches('matchOffset')
        )));
        return $base;
    }

    private function baseAllUnmatched(): Base
    {
        return $this->baseAllWith(new RawMatchesOffset([[]]));
    }

    private function baseAll(): Base
    {
        return $this->baseAllWith($this->matchesOffset());
    }

    private function baseAllWith(IRawMatchesOffset $matches): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn($matches);
        $base->expects($this->never())->method($this->logicalNot($this->matches('matchAllOffsets')));
        return $base;
    }

    private function zeroInteraction(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->never())->method($this->anything());
        return $base;
    }

    private function baseFirst(): Base
    {
        return $this->baseFirstWith($this->matchOffset());
    }

    private function baseFirstUnmatched(): Base
    {
        return $this->baseFirstWith(new RawMatchOffset([]));
    }

    private function baseFirstWith(IRawMatchOffset $match): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchOffset')->willReturn($match);
        $base->expects($this->never())->method($this->logicalNot($this->matches('matchOffset')));
        return $base;
    }

    private function matchesOffset(): IRawMatchesOffset
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
