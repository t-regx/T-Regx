<?php
namespace Test\Feature\CleanRegex\Match\Details\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupName;
use TRegx\CleanRegex\Match\Details\Group\Group;
use TRegx\CleanRegex\Pattern;

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
        $matchGroup = $this->exampleMatchedGroup();
        // when
        $text = $matchGroup->text();
        // then
        $this->assertSame('Nice matching', $text);
    }

    /**
     * @test
     */
    public function shouldBeMatched()
    {
        // given
        $matchGroup = $this->exampleMatchedGroup();
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
        $matchGroup = $this->exampleMatchedGroup();
        // when, then
        $this->assertTrue($matchGroup->equals('Nice matching'));
        $this->assertFalse($matchGroup->equals('some other'));
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $matchGroup = Pattern::of('(Łu)kasz')->match('ść Łukasz ść')->first()->group(1);
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
        $matchGroup = Pattern::of('(Łu)kaśz')->match('ść Łukaśz ść')->first()->group(1);
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
        $matchGroup = $this->exampleMatchedGroup();
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
        $matchGroup = $this->exampleMatchedGroup();
        // when
        $index = $matchGroup->index();
        // then
        $this->assertSame(1, $index);
    }

    /**
     * @test
     */
    public function shouldSubstituteGroup()
    {
        // given
        $matchGroup = $this->exampleMatchedGroup();
        // when
        $result = $matchGroup->substitute('<replaced value>');
        // then
        $this->assertSame('start:<replaced value>:end', $result);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $matchGroup = $this->exampleMatchedGroup();
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
        $matchGroup = $this->exampleMatchedGroup();
        // when
        $orReturn = $matchGroup->or('13');
        // then
        $this->assertSame('Nice matching', $orReturn);
    }

    /**
     * @test
     * @dataProvider identifiers
     * @param GroupKey $group
     */
    public function shouldGetUsedIdentifier(GroupKey $group)
    {
        // given
        $matchGroup = $this->exampleMatchedGroup($group);
        // when
        $identifier = $matchGroup->usedIdentifier();
        // then
        $this->assertSame($group->nameOrIndex(), $identifier);
    }

    public function identifiers(): array
    {
        return [
            [new GroupName('first')],
            [new GroupIndex(1)],
        ];
    }

    /**
     * @test
     * @dataProvider integers
     */
    public function shouldParseIntegerBase10Default(string $text, int $expected, array $arguments)
    {
        // given
        $matchedGroup = Pattern::of('(\w+)')->match($text)->first()->group(1);
        // when
        $integer = $matchedGroup->toInt(...$arguments);
        // then
        $this->assertSame($expected, $integer);
    }

    public function integers(): array
    {
        return [
            ['194', 194, []],
            ['194', 194, [10]],
            ['a4f', 2639, [16]],
        ];
    }

    /**
     * @test
     */
    public function shouldToIntThrowForInvalidBase()
    {
        // given
        $matchedGroup = $this->exampleMatchedGroup();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -2 (supported bases 2-36, case-insensitive)');
        // when
        $matchedGroup->toInt(-2);
    }

    /**
     * @test
     */
    public function shouldIsIntThrowForInvalidBase()
    {
        // given
        $matchedGroup = $this->exampleMatchedGroup();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: -2 (supported bases 2-36, case-insensitive)');
        // when
        $matchedGroup->isInt(-2);
    }

    private function exampleMatchedGroup(GroupKey $group = null): Group
    {
        $subject = 'before- start:Nice matching:end -after';
        $pattern = 'start:(?<first>Nice matching):end';
        $detail = Pattern::of($pattern)->match($subject)->first();
        return $detail->group($group ? $group->nameOrIndex() : 'first');
    }
}
