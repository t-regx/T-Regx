<?php
namespace Test\Feature\CleanRegex\Match\Details\group;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Runtime\ExplicitStringEncoding;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use function pattern;

class MatchDetailTest extends TestCase
{
    use ExplicitStringEncoding;

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $detail = pattern('Hello (?<one>there)')->match('Hello there, General Kenobi')->first();
        // when, then
        $this->assertSame('there', "" . $detail->group('one'));
        $this->assertSame('there', $detail->group('one')->text());
        $this->assertSame(6, $detail->group('one')->offset());
        $this->assertTrue($detail->group('one')->matched());
        $this->assertTrue($detail->groupExists('one'));
        $this->assertFalse($detail->groupExists('two'));
    }

    /**
     * @test
     */
    public function shouldGet_groupTextLength()
    {
        // given
        $detail = pattern('(\p{L}+)', 'u')->match('Łomża')->first();
        // then
        $this->assertSame('Łomża', $detail->group(1)->text());
        $this->assertSame(5, $detail->group(1)->length());
        $this->assertSame(7, $detail->group(1)->byteLength());
    }

    /**
     * @test
     */
    public function shouldGet_all_matched()
    {
        // given
        $detail = pattern('Hello (?<one>there|here)?')->match('Hello there, General Kenobi, maybe Hello and Hello here')->first();
        // when
        $all = $detail->all();
        // then
        $this->assertSame(['Hello there', 'Hello ', 'Hello here'], $all);
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_matched()
    {
        // given
        $detail = pattern('Hello (?<one>there|here)?')->match('Hello there, General Kenobi, maybe Hello and Hello here')->first();
        // when
        $all = $detail->group('one')->all();
        // then
        $this->assertSame(['there', null, 'here'], $all);
    }

    /**
     * @test
     */
    public function shouldGetGroup_all_unmatched()
    {
        // given
        $detail = pattern('Hello (?<one>there|here)?')->match('Hello , General Kenobi, maybe Hello there and Hello here')->first();
        // when
        $groupAll = $detail->group('one')->all();
        // then
        $this->assertSame([null, 'there', 'here'], $groupAll);
    }

    /**
     * @test
     */
    public function shouldGetGroup_empty_string()
    {
        // given
        $detail = pattern('Hello (?<one>there|here|)')->match('Hello there, General Kenobi, maybe Hello and Hello here')->first();
        // when
        $all = $detail->all();
        $groupAll = $detail->group('one')->all();
        // then
        $this->assertSame(['Hello there', 'Hello ', 'Hello here'], $all);
        $this->assertSame(['there', '', 'here'], $groupAll);
    }

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = pattern('(?<matched>Foo)(?<unmatched>Bar)?')->match('Hello:Foo')->first();
        // when
        $matchedSubject = $detail->group('matched')->subject();
        $unmatchedSubject = $detail->group('unmatched')->subject();
        // then
        $this->assertSame('Hello:Foo', $matchedSubject);
        $this->assertSame('Hello:Foo', $unmatchedSubject);
    }

    /**
     * @test
     * @dataProvider shouldGroup_notMatch_dataProvider
     * @param string $pattern
     * @param string $subject
     */
    public function shouldGroup_notMatch(string $pattern, string $subject)
    {
        // given
        $detail = pattern($pattern)->match($subject)->first();
        // when
        $matches = $detail->group('one')->matched();
        // then
        $this->assertFalse($matches);
    }

    public function shouldGroup_notMatch_dataProvider(): array
    {
        return [
            ['Hello (?<one>there)?', 'Hello XX, General Kenobi'],
            ['Hello (?<one>there)?(?<two>XX)', 'Hello XX, General Kenobi'],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_onMissingGroup()
    {
        // given
        $detail = pattern('(?<one>hello)')->match('hello')->first();
        // then
        $this->expectException(NonexistentGroupException::class);
        $this->expectExceptionMessage("Nonexistent group: 'two'");
        // when
        $detail->group('two');
    }

    /**
     * @test
     */
    public function shouldValidateGroupName()
    {
        // given
        $detail = pattern('(?<one>first) and (?<two>second)')->match('first and second')->first();
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Group index must be an integer or a string, but boolean (true) given');
        // when
        $detail->group(true);
    }

    /**
     * @test
     */
    public function shouldThrowGroupNotMatchedException()
    {
        // given
        $detail = pattern('(?<group>Foo)?')->match('Bar')->first();
        // then
        $this->expectException(GroupNotMatchedException::class);
        $this->expectExceptionMessage("Expected to call text() for group 'group', but the group was not matched");
        // when
        $detail->group('group')->text();
    }
}
