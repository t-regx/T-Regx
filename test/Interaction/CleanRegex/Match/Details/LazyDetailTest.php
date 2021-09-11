<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\Details;

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchesOffset;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\LazyDetail;

/**
 * @covers \TRegx\CleanRegex\Match\Details\LazyDetail
 */
class LazyDetailTest extends TestCase
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
        $this->assertSame($expected, $result);
    }

    public function methods(): array
    {
        return [
            ['text', [], 'word€€'],
            ['textLength', [], 6],
            ['textByteLength', [], 10],
            ['limit', [], 14],
            ['matched', [0], true],
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
        $this->assertSame('word€€', $result);
    }

    /**
     * @test
     */
    public function shouldIndex()
    {
        // given
        $detail = $this->detailWithIndex('[a-z]+', 'one, two, three', 2);

        // when
        $text = $detail->text();
        $index = $detail->index();

        // then
        $this->assertSame('three', $text);
        $this->assertSame(2, $index);
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
        $this->assertSame(123, $int);
    }

    /**
     * @test
     */
    public function shouldToIntBase16()
    {
        // given
        $detail = $this->detail('\w+', '123cb');

        // when
        $int = $detail->toInt(16);

        // then
        $this->assertSame(74699, $int);
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidBase()
    {
        // given
        $detail = $this->detail('\d+', '123cm');

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid base: 38 (supported bases 2-36, case-insensitive)');

        // when
        $detail->toInt(38);
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
        $this->assertSame(['first', 'second'], $result);
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
        $this->assertSame(2, $count);
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
    public function shouldBeInt()
    {
        // given
        $detail = $this->detail('\d+', '9');

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldNoBeInt()
    {
        // given
        $detail = $this->detail('1a', '1a');

        // when
        $isInt = $detail->isInt();

        // then
        $this->assertFalse($isInt);
    }

    /**
     * @test
     */
    public function shouldBeIntBase36()
    {
        // given
        $detail = $this->detail('azb', 'azb');

        // when
        $isInt = $detail->isInt(36);

        // then
        $this->assertTrue($isInt);
    }

    /**
     * @test
     */
    public function shouldNoBeInt2()
    {
        // given
        $detail = $this->detail('2', '2');

        // when
        $isInt = $detail->isInt(2);

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
        $this->assertSame('one', $result);
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
        $this->assertSame(['one', null], $result);
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
        $this->assertSame(['first' => 'one', 'second' => 'two'], $result);
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
        $this->assertSame('welcome', $result);
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
        $this->assertSame(2, $offset);
        $this->assertSame(4, $byteOffset);
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
        $this->assertSame(4, $tail);
        $this->assertSame(7, $byteTail);
    }

    /**
     * @test
     */
    public function shouldCallBaseOnce()
    {
        // given
        $detail = new LazyDetail($this->baseMock(), 0, -1);

        // when
        $detail->text();
        $detail->get(0);
        $detail->byteOffset();
    }

    /**
     * @test
     */
    public function shouldDuplicateGroups()
    {
        // given
        $pattern = '(?<group>One)(?<group>Two)';
        $detail = new LazyDetail(new ApiBase(Definitions::pattern($pattern, 'J'), new StringSubject('OneTwo'), new UserData()), 0, -1);

        // when
        $text1 = $detail->group('group')->text();
        $text2 = $detail->usingDuplicateName()->group('group')->text();

        // then
        $this->assertSame('One', $text1);
        $this->assertSame('Two', $text2);
    }

    private function detail(string $pattern = '\b[a-z€]+', string $subject = 'Word: word€€ two three'): LazyDetail
    {
        return $this->detailWithIndex($pattern, $subject, 0);
    }

    private function detailWithIndex(string $pattern, string $subject, int $index): LazyDetail
    {
        return new LazyDetail(new ApiBase(Definitions::pattern($pattern, 'u'), new StringSubject($subject), new UserData()), $index, 14);
    }

    private function baseMock(): Base
    {
        /** @var Base|MockObject $base */
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('matchAllOffsets')->willReturn(new RawMatchesOffset([[['', 14]]]));
        return $base;
    }
}
