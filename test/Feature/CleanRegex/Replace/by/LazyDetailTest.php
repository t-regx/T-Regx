<?php
namespace Test\Feature\TRegx\CleanRegex\Replace\by;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\Replace\By\LazyDetail;
use TRegx\CleanRegex\Replace\ReplacePattern;

/**
 * @covers \TRegx\CleanRegex\Replace\By\LazyDetail
 */
class LazyDetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetText()
    {
        // given
        $detail = $this->detail(Pattern::of('Foo(Bar)?')->replace('Foo'));
        // when
        $text = $detail->text();
        // then
        $this->assertSame('Foo', $text);
    }

    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = $this->detail(Pattern::of('Foo(Bar)?')->replace('Foo, my little friend'));
        // when
        $text = $detail->subject();
        // then
        $this->assertSame('Foo, my little friend', $text);
    }

    /**
     * @test
     */
    public function shouldCastToString()
    {
        // given
        $detail = $this->detail(Pattern::of('Foo(Bar)?')->replace('Foo'));
        // when
        $cast = (string)$detail;
        // then
        $this->assertSame('Foo', $cast);
    }

    /**
     * @test
     */
    public function shouldGetOtherMatches()
    {
        // given
        $detail = $this->detail(Pattern::of('\d+(Bar)?')->replace('12, 13, 14'));
        // when
        $texts = $detail->all();
        // then
        $this->assertSame(['12', '13', '14'], $texts);
    }

    /**
     * @test
     */
    public function shouldGetIndex()
    {
        // given
        [$eleven, $twelve, $thirteen] = $this->details(Pattern::of('\d+(Bar)?')->replace('12, 13, 14'));
        // when
        $first = $eleven->index();
        $second = $twelve->index();
        $third = $thirteen->index();
        // then
        $this->assertSame(0, $first);
        $this->assertSame(1, $second);
        $this->assertSame(2, $third);
    }

    /**
     * @test
     */
    public function shouldGetLimit()
    {
        // given
        $detail = $this->detail(Pattern::of('\d+(Bar)?')->replace('12, 13, 14')->only(13));
        // when
        $limit = $detail->limit();
        // then
        $this->assertSame(13, $limit);
    }

    /**
     * @test
     */
    public function shouldGetAsInteger()
    {
        // given
        $detail = $this->detail(Pattern::of('\d+(Bar)?')->replace('123cm'));
        // when
        $int = $detail->toInt();
        // then
        $this->assertSame(123, $int);
    }

    /**
     * @test
     * @depends shouldGetAsInteger
     */
    public function shouldToIntBase16()
    {
        // given
        $detail = $this->detail(Pattern::of('\w+(Bar)?')->replace('123cb'));
        // when
        $integer = $detail->toInt(16);
        // then
        $this->assertSame(74699, $integer);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase_toInt()
    {
        // given
        $detail = $this->detail(Pattern::of('\w+(Bar)?')->replace('123cb'));
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 38 (supported bases 2-36, case-insensitive)');
        // when
        $detail->toInt(38);
    }

    /**
     * @test
     */
    public function shouldBeInt()
    {
        // given
        $detail = $this->detail(Pattern::of('\d+(Bar)?')->replace('123cm'));
        // when
        $isInteger = $detail->isInt();
        // then
        $this->assertTrue($isInteger);
    }

    /**
     * @test
     */
    public function shouldNoBeInt()
    {
        // given
        $detail = $this->detail(Pattern::of('1a(Bar)?')->replace('1a'));
        // when
        $isInteger = $detail->isInt();
        // then
        $this->assertFalse($isInteger);
    }

    /**
     * @test
     */
    public function shouldBeIntBase36()
    {
        // given
        $detail = $this->detail(Pattern::of('azb(Bar)?')->replace('azb'));
        // when
        $isInteger = $detail->isInt(36);
        // then
        $this->assertTrue($isInteger);
    }

    /**
     * @test
     */
    public function shouldNoBeInt2()
    {
        // given
        $detail = $this->detail(Pattern::of('2(Bar)?')->replace('2'));
        // when
        $isInteger = $detail->isInt(2);
        // then
        $this->assertFalse($isInteger);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase_isInt()
    {
        // given
        $detail = $this->detail(Pattern::of('\w+(Bar)?')->replace('123cb'));
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 38 (supported bases 2-36, case-insensitive)');
        // when
        $detail->isInt(38);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $names = $detail->groupNames();
        // then
        $this->assertSame([null, 'first', 'second'], $names);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $count = $detail->groupsCount();
        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldHaveGroup()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $hasGroup = $detail->hasGroup('second');
        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldNotHaveGroup()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $hasGroup = $detail->hasGroup('foo');
        // then
        $this->assertFalse($hasGroup);
    }

    /**
     * @test
     */
    public function shouldHaveGroupMatched()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $hasGroup = $detail->matched('first');
        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldNotHaveGroupMatched()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)!')->replace('!one!'));
        // when
        $hasGroup = $detail->matched(1);
        // then
        $this->assertFalse($hasGroup);
    }

    /**
     * @test
     */
    public function shouldGetGroup_text()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)!')->replace('!one!'));
        // when
        $group = $detail->group('first')->text();
        // then
        $this->assertSame('one', $group);
    }

    /**
     * @test
     */
    public function shouldGetGroup_get()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)!')->replace('!one!'));
        // when
        $group = $detail->get('first');
        // then
        $this->assertSame('one', $group);
    }

    /**
     * @test
     */
    public function shouldGetGroupsFirstMissing()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one),(?<second>two)!')->replace('!one,two!'), 1);
        // when
        $result = $detail->groups()->texts();
        // then
        $this->assertSame([null, 'one', 'two'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupsMiddleMissing()
    {
        // given
        $detail = $this->detail(Pattern::of('!(?<first>one),(Bar)?(?<second>two)!')->replace('!one,two!'), 2);
        // when
        $result = $detail->groups()->texts();
        // then
        $this->assertSame(['one', null, 'two'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupsLastMissing()
    {
        // given
        $detail = $this->detail(Pattern::of('!(?<first>one),(?<second>two)!(Bar)?')->replace('!one,two!'), 3);
        // when
        $result = $detail->groups()->texts();
        // then
        $this->assertSame(['one', 'two', null], $result);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?!(?<first>one)(?<second>two)!')->replace('!onetwo!'));
        // when
        $result = $detail->namedGroups()->texts();
        // then
        $this->assertSame(['first' => 'one', 'second' => 'two'], $result);
    }

    /**
     * @test
     */
    public function shouldGetUserData()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?Foo')->replace('Foo'));
        // when
        $detail->setUserData('welcome');
        $result = $detail->getUserData();
        // then
        $this->assertSame('welcome', $result);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?Morty')->replace('€ Morty'));
        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();
        // then
        $this->assertSame(2, $offset);
        $this->assertSame(4, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?Źrebię')->replace('€ Źrebię'));
        // when
        $tail = $detail->tail();
        $byteTail = $detail->byteTail();
        // then
        $this->assertSame(8, $tail);
        $this->assertSame(12, $byteTail);
    }

    /**
     * @test
     */
    public function shouldGetLength()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?Źrebię')->replace('€ Źrebię'));
        // when
        $tail = $detail->textLength();
        $byteTail = $detail->textByteLength();
        // then
        $this->assertSame(6, $tail);
        $this->assertSame(8, $byteTail);
    }

    /**
     * @test
     */
    public function shouldDuplicateGroups()
    {
        // given
        $detail = $this->detail(Pattern::of('(Bar)?(?<group>One)(?<group>Two)', 'J')->replace('OneTwo'));
        // when
        $text1 = $detail->get('group');
        $text2 = $detail->usingDuplicateName()->get('group');
        // then
        $this->assertSame('One', $text1);
        $this->assertSame('Two', $text2);
    }

    private function detail(ReplacePattern $pattern, int $group = null): LazyDetail
    {
        $pattern->by()->group($group ?? 1)->orElseCalling(Functions::out($detail, ''));
        $this->assertNotNull($detail, "Failed to assert that subject was matched without group #$group");
        return $detail;
    }

    /**
     * @param ReplacePattern $pattern
     * @return LazyDetail[]
     */
    private function details(ReplacePattern $pattern): array
    {
        $pattern->by()->group(1)->orElseCalling(Functions::collect($details, ''));
        return $details;
    }
}