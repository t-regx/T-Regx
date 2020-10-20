<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchedGroupOccurrence;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;

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
    public function shouldEqual()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when + then
        $this->assertTrue($matchGroup->equals("Nice matching"));
        $this->assertFalse($matchGroup->equals("some other"));
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $matchGroup = $this->buildMatchGroup("ść Łukasz ść", "Łukasz", "Łu", 1, 5);

        // when
        $offset = $matchGroup->offset();
        $byteOffset = $matchGroup->byteOffset();

        // then
        $this->assertEquals(3, $offset);
        $this->assertEquals(5, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $matchGroup = $this->buildMatchGroup("ść Łukaśz ść", "Łukaśz", "Łu", 1, 5);

        // when
        $tail = $matchGroup->tail();
        $byteTail = $matchGroup->byteTail();

        // then
        $this->assertEquals(5, $tail);
        $this->assertEquals(8, $byteTail);
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
    public function shouldReplaceGroup()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $match = $matchGroup->replace('<replaced value>');

        // then
        $this->assertEquals('start(<replaced value>)end', $match);
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
        $matchGroup = $this->buildMatchGroup('before- start(Nice matching)end -after match', 'start(Nice matching)end', 'Nice matching', $usedIdentifier, 14);

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

    private function matchGroup(): MatchedGroup
    {
        return $this->buildMatchGroup(
            'before- start(Nice matching)end -after match',
            'start(Nice matching)end',
            'Nice matching',
            'first',
            14);
    }

    private function buildMatchGroup(string $subject, string $match, string $group, $nameOrIndex, $groupOffset): MatchedGroup
    {
        return new MatchedGroup(
            new RawMatchOffset([
                0       => [$match, 8],
                'first' => [$group, $groupOffset],
                1       => [$group, $groupOffset]
            ]),
            new GroupDetails('first', 1, $nameOrIndex, new EagerMatchAllFactory(new RawMatchesOffset([]))),
            new MatchedGroupOccurrence($group, $groupOffset, new Subject($subject))
        );
    }
}
