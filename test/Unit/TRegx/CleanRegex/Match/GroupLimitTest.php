<?php
namespace Test\Unit\TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Match\GroupLimit;
use PHPUnit\Framework\TestCase;
use Test\ClosureMock;

class GroupLimitTest extends TestCase
{
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
     * @test
     */
    public function shouldReturnValues()
    {
        // given
        $all = new ClosureMock(function () {
            return ['first', 'second', 'third'];
        });
        $first = new ClosureMock(function () {
            return 'first';
        });
        $limit = new GroupLimit($all, $first);

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
     * @return array
     */
    private function getGroupLimit(): array
    {
        $all = new ClosureMock(function () {
            return [];
        });
        $first = new ClosureMock(function () {
            return null;
        });
        return [new GroupLimit($all, $first), $all, $first];
    }
}
