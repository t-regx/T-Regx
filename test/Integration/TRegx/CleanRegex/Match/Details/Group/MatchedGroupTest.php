<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\MatchAllResults;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\SubjectableEx;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class MatchedGroupTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $text = $matchGroup->text();

        // then
        $this->assertEquals('Nice matching', $text);
    }

    /**
     * @test
     */
    public function shouldMatch()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $matches = $matchGroup->matched();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $offset = $matchGroup->offset();

        // then
        $this->assertEquals(14, $offset);
    }

    /**
     * @test
     */
    public function shouldGetName()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $name = $matchGroup->name();

        // then
        $this->assertEquals('first', $name);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $index = $matchGroup->index();

        // then
        $this->assertEquals(1, $index);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $text = (string)$matchGroup;

        // then
        $this->assertEquals('Nice matching', $text);
    }

    /**
     * @test
     */
    public function shouldControlMatched()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orElse = $matchGroup->orElse('strtoupper');
        $orReturn = $matchGroup->orReturn(13);
        $orThrow = $matchGroup->orThrow();

        // then
        $this->assertEquals('Nice matching', $orElse);
        $this->assertEquals('Nice matching', $orReturn);
        $this->assertEquals('Nice matching', $orThrow);
    }

    /**
     * @test
     * @dataProvider identifiers
     * @param int|string $usedIdentifier
     */
    public function shouldGet_usedIdentifier($usedIdentifier)
    {
        // given
        $matchGroup = $this->matchGroupWithIndexAndName($usedIdentifier);

        // when
        $result = $matchGroup->usedIdentifier();

        // then
        $this->assertEquals($usedIdentifier, $result);
    }

    public function identifiers(): array
    {
        return [
            ['first'],
            [1],
        ];
    }

    private function matchGroup(): MatchGroup
    {
        return $this->matchGroupWithIndexAndName('first');
    }

    private function matchGroupWithIndexAndName($nameOrIndex): MatchGroup
    {
        return new MatchedGroup(
            new GroupDetails('first', 1, $nameOrIndex, new MatchAllResults(new RawMatchesOffset([]), 'first')),
            new MatchedGroupOccurrence('Nice matching', 14, new SubjectableImpl(str_repeat(' ', 14)))
        );
    }
}
