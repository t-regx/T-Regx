<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\ClosureMock;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnValues(): void
    {
        // given
        $all = new ClosureMock(function () {
            return new RawMatchesOffset([0 => [['first', 0], ['second', 1], ['third', 2]]]);
        });
        $first = new ClosureMock(function () {
            return 'first';
        });
        $limit = new GroupLimit($all, $first, new MatchOffsetLimitFactory(new ApiBase(new InternalPattern(''), '', new UserData()), 0, false), new Subject('unused'), 0);

        // when
        $fromAll = $limit->all();
        $fromOnly = $limit->only(2);
        $fromFirst = $limit->first();

        // then
        $this->assertEquals($fromAll, ['first', 'second', 'third']);
        $this->assertEquals($fromOnly, ['first', 'second']);
        $this->assertEquals($fromFirst, 'first');
    }

    /**
     * @test
     */
    public function shouldReturnValuesFromIterator(): void
    {
        // given
        /** @var $limit GroupLimit */
        list($limit, $all, $first) = $this->mockGroupLimit([['Foo', 1], ['Bar', 2]]);

        // when
        $iterator = $limit->iterator();

        // then
        $this->assertEquals(['Foo', 'Bar'], iterator_to_array($iterator));
        $this->assertTrue($all->isCalled(), 'Failed asserting that all() factory is called');
        $this->assertFalse($first->isCalled(), 'Failed asserting that first() factory is not called unnecessarily');
    }

    /**
     * @test
     * @dataProvider allCallingMethods
     * @param string $method
     * @param array $arguments
     */
    public function shouldCallAll(string $method, array $arguments)
    {
        // given
        /** @var $limit GroupLimit */
        list($limit, $all, $first) = $this->mockGroupLimit();

        // when
        $limit->$method(...$arguments);

        // then
        $this->assertTrue($all->isCalled(), 'Failed asserting that all() factory is called');
        $this->assertFalse($first->isCalled(), 'Failed asserting that first() factory is not called unnecessarily');
    }

    function allCallingMethods()
    {
        return [
            ['all', []],
            ['only', [14]],
            ['map', [function () {
            }]],
            ['forEach', [function () {
            }]],
            ['iterate', [function () {
            }]],
            ['iterator', []],
        ];
    }

    /**
     * @test
     */
    public function shouldCallFirst()
    {
        // given
        /** @var $limit GroupLimit */
        list($limit, $all, $first) = $this->mockGroupLimit();

        // when
        $limit->first();

        // then
        $this->assertTrue($first->isCalled(), 'Failed asserting that first() factory is called');
        $this->assertFalse($all->isCalled(), 'Failed asserting that all() factory is not called unnecessarily');
    }

    private function mockGroupLimit(array $allValues = []): array
    {
        $all = new ClosureMock(function () use ($allValues) {
            return new RawMatchesOffset([0 => $allValues]);
        });
        $first = new ClosureMock(function () {
            return '';
        });
        return [new GroupLimit($all, $first, new MatchOffsetLimitFactory(new ApiBase(new InternalPattern(''), '', new UserData()), 0, false), new Subject('unused'), 0), $all, $first];
    }
}
