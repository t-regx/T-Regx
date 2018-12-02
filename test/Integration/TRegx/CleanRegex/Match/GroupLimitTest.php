<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\ClosureMock;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnValues()
    {
        // given
        $all = new ClosureMock(function (int $limit, bool $allowNegative) {
            if ($limit === -1) {
                $this->assertTrue($allowNegative);
                return ['first', 'second', 'third'];
            }
            if ($limit === 2) {
                $this->assertFalse($allowNegative);
                return ['first', 'second'];
            }
            $this->assertFalse(true);
            return null;
        });
        $first = new ClosureMock(function () {
            return 'first';
        });
        $limit = new GroupLimit($all, $first, new MatchOffsetLimitFactory(new ApiBase(new InternalPattern(''), '', new UserData()), 0));

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
    public function shouldCallGetAll()
    {
        // given
        list($limit, $all, $first) = $this->getGroupLimit();

        // when
        $limit->all();

        // then
        $this->assertTrue($all->isCalled(), 'Failed asserting that all() factory is called');
        $this->assertFalse($first->isCalled(), 'Failed asserting that first() factory is not called unnecessarily');
    }

    /**
     * @test
     */
    public function shouldCallOnly()
    {
        // given
        list($limit, $all, $first) = $this->getGroupLimit();

        // when
        $limit->only(14);

        // then
        $this->assertTrue($all->isCalled(), 'Failed asserting that all() factory is called');
        $this->assertFalse($first->isCalled(), 'Failed asserting that first() factory is not called unnecessarily');
    }

    /**
     * @test
     */
    public function shouldCallFirst()
    {
        // given
        list($limit, $all, $first) = $this->getGroupLimit();

        // when
        $limit->first();

        // then
        $this->assertTrue($first->isCalled(), 'Failed asserting that first() factory is called');
        $this->assertFalse($all->isCalled(), 'Failed asserting that all() factory is not called unnecessarily');
    }

    /**
     * @return array
     */
    private function getGroupLimit(): array
    {
        $all = new ClosureMock(function () {
            return [];
        });
        $first = new ClosureMock(function () {
            return '';
        });
        return [new GroupLimit($all, $first, new MatchOffsetLimitFactory(new ApiBase(new InternalPattern(''), '', new UserData()), 0)), $all, $first];
    }
}
