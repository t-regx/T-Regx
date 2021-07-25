<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details\Group;

use PHPUnit\Framework\TestCase;
use Test\Utils\Impl\ConstantMatchEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupDetails;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupEntry;
use TRegx\CleanRegex\Internal\Match\Details\Group\SubstitutedGroup;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;

/**
 * @covers \TRegx\CleanRegex\Match\Details\Group\MatchedGroup
 */
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
        $this->assertSame('Nice matching', $text);
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
        $this->assertSame(3, $offset);
        $this->assertSame(5, $byteOffset);
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
        $this->assertSame(5, $tail);
        $this->assertSame(8, $byteTail);
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
        $this->assertSame('first', $name);
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
        $this->assertSame(1, $index);
    }

    /**
     * @test
     */
    public function shouldReplaceGroup()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $result = $matchGroup->substitute('<replaced value>');

        // then
        $this->assertSame('start(<replaced value>)end', $result);
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
        $this->assertSame('Nice matching', $text);
    }

    /**
     * @test
     */
    public function shouldControlMatched()
    {
        // given
        $matchGroup = $this->matchGroup();

        // when
        $orElse = $matchGroup->orElse('strToUpper');
        $orReturn = $matchGroup->orReturn(13);
        $orThrow = $matchGroup->orThrow();

        // then
        $this->assertSame('Nice matching', $orElse);
        $this->assertSame('Nice matching', $orReturn);
        $this->assertSame('Nice matching', $orThrow);
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
        $this->assertSame($usedIdentifier, $result);
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
        $matchedGroup = new GroupEntry($group, $groupOffset, new Subject($subject));
        return new MatchedGroup(
            new Subject($subject),
            new GroupDetails('first', 1, $nameOrIndex, new EagerMatchAllFactory(new RawMatchesOffset([]))),
            $matchedGroup,
            new SubstitutedGroup(new ConstantMatchEntry($match, 8), $matchedGroup));
    }
}
