<?php
namespace Test\Integration\TRegx\CleanRegex\Match\Details;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\Details\LazyDetailImpl;

class LazyDetailImplTest extends TestCase
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
        $detail = $this->detail();

        // when
        $result = $detail->$method(...$arguments);

        // then
        $this->assertEquals($expected, $result);
    }

    public function methods(): array
    {
        return [
            ['text', [], 'word€€'],
            ['textLength', [], 6],
            ['textByteLength', [], 10],
            ['limit', [], 14],
            ['subject', [], 'Word: word€€ two three'],
            ['all', [], ['word€€', 'two', 'three']],
        ];
    }

    /**
     * @test
     */
    public function shouldText_castToString()
    {
        // given
        $detail = $this->detail();

        // when
        $result = (string)$detail;

        // then
        $this->assertEquals('word€€', $result);
    }

    /**
     * @test
     */
    public function shouldIndex()
    {
        // given
        $detail = $this->detailWithIndex('\w+', 'One, two, three', 2);

        // when
        $text = $detail->text();
        $index = $detail->index();

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
        $detail = $this->detail('\d+', '123cm');

        // when
        $int = $detail->toInt();

        // then
        $this->assertEquals(123, $int);
    }

    /**
     * @test
     */
    public function shouldGetGroupNames()
    {
        // given
        $detail = $this->detail('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $result = $detail->groupNames();

        // then
        $this->assertEquals(['first', 'second'], $result);
    }

    /**
     * @test
     */
    public function shouldGetGroupsCount()
    {
        // given
        $detail = $this->detail('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $count = $detail->groupsCount();

        // then
        $this->assertEquals(2, $count);
    }

    /**
     * @test
     */
    public function shouldHasGroup_true()
    {
        // given
        $detail = $this->detail('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $hasGroup = $detail->hasGroup('second');

        // then
        $this->assertTrue($hasGroup);
    }

    /**
     * @test
     */
    public function shouldHasGroup_false()
    {
        // given
        $detail = $this->detail('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $hasGroup = $detail->hasGroup('foo');

        // then
        $this->assertFalse($hasGroup);
    }

    /**
     * @test
     */
    public function shouldIsInt_true()
    {
        // given
        $detail = $this->detail('\d+', '!123!');

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldIsInt_false()
    {
        // given
        $detail = $this->detail('\w+', '!123e4!');

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertFalse($isInt);
    }

    /**
     * @test
     */
    public function shouldGetGroup()
    {
        // given
        $detail = $this->detail('!(?<first>one)!', '!one!');

        // when
        $result = $detail->group('first')->text();

        // then
        $this->assertEquals('one', $result);
    }

    /**
     * @test
     */
    public function shouldGetGroups()
    {
        // given
        $detail = $this->detail('(one)(two)?', 'one');

        // when
        $result = $detail->groups()->texts();

        // then
        $this->assertEquals(['one', null], $result);
    }

    /**
     * @test
     */
    public function shouldGetNamedGroups()
    {
        // given
        $detail = $this->detail('!(?<first>one)(?<second>two)!', '!onetwo!');

        // when
        $result = $detail->namedGroups()->texts();

        // then
        $this->assertEquals(['first' => 'one', 'second' => 'two'], $result);
    }

    /**
     * @test
     */
    public function shouldGetUserData()
    {
        // given
        $detail = $this->detail();

        // when
        $detail->setUserData('welcome');
        $result = $detail->getUserData();

        // then
        $this->assertEquals('welcome', $result);
    }

    /**
     * @test
     */
    public function shouldGetOffset()
    {
        // given
        $detail = $this->detail('2', '€ 2');

        // when
        $offset = $detail->offset();
        $byteOffset = $detail->byteOffset();

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
        $detail = $this->detail('2ę', '€ 2ę');

        // when
        $tail = $detail->tail();
        $byteTail = $detail->byteTail();

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
        $detail = new LazyDetailImpl($this->baseMock(), 0, -1);

        // when
        $detail->text();
        $detail->get(0);
        $detail->offset();
    }

    /**
     * @test
     */
    public function shouldDuplicateGroups()
    {
        // given
        $pattern = '(?<group>One)(?<group>Two)';
        $subject = 'OneTwo';
        $detail = new LazyDetailImpl(new ApiBase(InternalPattern::standard($pattern, 'J'), $subject, new UserData()), 0, -1);

        // when
        $text1 = $detail->group('group')->text();
        $text2 = $detail->usingDuplicateName()->group('group')->text();

        // then
        $this->assertEquals('One', $text1);
        $this->assertEquals('Two', $text2);
    }

    private function detail(string $pattern = '\b[a-z€]+', string $subject = 'Word: word€€ two three'): LazyDetailImpl
    {
        return $this->detailWithIndex($pattern, $subject, 0);
    }

    private function detailWithIndex(string $pattern, string $subject, int $index): LazyDetailImpl
    {
        return new LazyDetailImpl(new ApiBase(InternalPattern::standard($pattern, 'u'), $subject, new UserData()), $index, 14);
    }

    private function baseMock(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn(new RawMatchesOffset([[['', 14]]]));
        return $base;
    }
}
