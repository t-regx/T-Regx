<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\Details\LazyMatchImpl;

class LazyMatchImplTest extends TestCase
{
    /**
     * @test
     * @dataProvider methods
     * @param string $method
     * @param array $arguments
     * @param $expected
     */
    public function testMethod(string $method, array $arguments, $expected)
    {
        // given
        $match = $this->match();

        // when
        $result = $match->$method(...$arguments);

        // then
        $this->assertEquals($expected, $result);
    }

    public function methods(): array
    {
        return [
            ['text', [], 'word'],
            ['textLength', [], 4],
            ['tail', [], 10],
            ['limit', [], 14],
            ['subject', [], 'Word: word two three'],
            ['all', [], ['word', 'two', 'three']],
        ];
    }

    /**
     * @test
     */
    public function shouldText_castToString()
    {
        // given
        $match = $this->match();

        // when
        $result = (string)$match;

        // then
        $this->assertEquals('word', $result);
    }

    /**
     * @test
     */
    public function shouldIndex()
    {
        // given
        $match = $this->matchWithIndex('\w+', 'One, two, three', 2);

        // when
        $text = $match->text();
        $index = $match->index();

        // then
        $this->assertEquals('three', $text);
        $this->assertEquals(2, $index);
    }

    /**
     * @test
     */
    public function shouldToInt()
    {
        // given
        $match = $this->match('\d+', '123cm');

        // when
        $int = $match->toInt();

        // then
        $this->assertEquals(123, $int);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $result = $match->groupNames();

        // then
        $this->assertEquals(['first', 'second'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $count = $match->groupsCount();

        // then
        $this->assertEquals(2, $count);
    }

    /**
     * @test
     */
    public function shouldHasGroup_true()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $hasGroup = $match->hasGroup('second');

        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldHasGroup_false()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $hasGroup = $match->hasGroup('foo');

        // then
        $this->assertFalse($hasGroup);
    }

    /**
     * @test
     */
    public function shouldIsInt_true()
    {
        // given
        $match = $this->match('\d+', '!123!');

        // when
        $isInt = $match->isInt();

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldIsInt_false()
    {
        // given
        $match = $this->match('\w+', '!123e4!');

        // when
        $isInt = $match->isInt();

        // then
        $this->assertFalse($isInt);
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $match = $this->match('!(?<first>one)!', '!one!');

        // when
        $result = $match->group('first')->text();

        // then
        $this->assertEquals('one', $result);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $match = $this->match('(one)(two)?', 'one');

        // when
        $result = $match->groups()->texts();

        // then
        $this->assertEquals(['one', null], $result);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $match = $this->match('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $result = $match->namedGroups()->texts();

        // then
        $this->assertEquals(['first' => 'one', 'second' => 'two'], $result);
    }

    /**
     * @test
     */
    public function shouldGetUserData()
    {
        // given
        $match = $this->match();

        // when
        $match->setUserData('welcome');
        $result = $match->getUserData();

        // then
        $this->assertEquals('welcome', $result);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $match = $this->match('2', '€ 2');

        // when
        $offset = $match->offset();
        $byteOffset = $match->byteOffset();

        // then
        $this->assertEquals(2, $offset);
        $this->assertEquals(4, $byteOffset);
    }

    /**
     * @test
     */
    public function shouldGetTail()
    {
        // given
        $match = $this->match('2ę', '€ 2ę');

        // when
        $tail = $match->tail();
        $byteTail = $match->byteTail();

        // then
        $this->assertEquals(4, $tail);
        $this->assertEquals(7, $byteTail);
    }

    /**
     * @test
     */
    public function shouldCallBaseOnce()
    {
        // given
        $match = new LazyMatchImpl($this->baseMock(), 0, -1);

        // when
        $match->text();
        $match->get(0);
        $match->offset();
    }

    private function match(string $pattern = '\b[a-z]+', string $subject = 'Word: word two three'): LazyMatchImpl
    {
        return $this->matchWithIndex($pattern, $subject, 0);
    }

    private function matchWithIndex(string $pattern, string $subject, int $index): LazyMatchImpl
    {
        return new LazyMatchImpl(new ApiBase(InternalPattern::standard($pattern, 'u'), $subject, new UserData()), $index, 14);
    }

    private function baseMock(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn(new RawMatchesOffset([[['', 14]]]));
        return $base;
    }
}
