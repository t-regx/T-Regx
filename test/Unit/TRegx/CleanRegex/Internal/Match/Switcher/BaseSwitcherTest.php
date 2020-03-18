<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Switcher\BaseSwitcher;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;

class BaseSwitcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetAll()
    {
        // given
        $switcher = new BaseSwitcher($this->baseAll());

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
        $switcher = new BaseSwitcher($this->baseFirst());

        // when
        $first = $switcher->first();

        // then
        $this->assertEquals('Joffrey', $first->getText());
    }

    /**
     * @test
     */
    public function shouldAll_callBase_once()
    {
        // given
        $switcher = new BaseSwitcher($this->baseAll());

        // when
        $switcher->all();
        $switcher->all();
        $switcher->all();
    }

    /**
     * @test
     */
    public function shouldFirst_callBase_once()
    {
        // given
        $switcher = new BaseSwitcher($this->baseFirst());

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
        $switcher = new BaseSwitcher($this->baseBoth());

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
        $switcher = new BaseSwitcher($this->baseAll());

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
        $switcher = new BaseSwitcher($this->baseBoth());

        // when
        $switcher->first();
        $switcher->all();
        $matchOffset = $switcher->first();

        // then
        $this->assertEquals('Joffrey', $matchOffset->getText());
    }

    private function baseBoth(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn($this->matchesOffset());
        $base->expects($this->once())->method('matchOffset')->willReturn($this->matchOffset());
        $base->expects($this->never())->method($this->logicalNot($this->logicalOr(
            $this->matches('matchAllOffsets'),
            $this->matches('matchOffset')
        )));
        return $base;
    }

    private function baseAll(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn($this->matchesOffset());
        $base->expects($this->never())->method($this->logicalNot($this->matches('matchAllOffsets')));
        return $base;
    }

    private function baseFirst(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchOffset')->willReturn($this->matchOffset());
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
