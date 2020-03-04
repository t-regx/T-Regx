<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnValues(): void
    {
        // given
        $limit = $this->mockGroupLimit([['first', 0], ['second', 1], ['third', 2]], 'first');

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
    public function shouldReturnValues_all(): void
    {
        // given
        $limit = $this->mockGroupLimit([['Foo', 1], ['Bar', 2]]);

        // when
        $result = $limit->all();

        // then
        $this->assertEquals(['Foo', 'Bar'], $result);
    }

    /**
     * @test
     */
    public function shouldReturnValues_iterator(): void
    {
        // given
        $limit = $this->mockGroupLimit([['Foo', 1], ['Bar', 2]]);

        // when
        $iterator = $limit->iterator();

        // then
        $this->assertEquals(['Foo', 'Bar'], iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldInvokeFirstConsumer()
    {
        // given
        $limit = $this->mockGroupLimit([], 'Foo Bar');

        // when
        $limit->first(function (MatchGroup $group) {
            // then
            $this->assertEquals('Foo Bar', $group->text());
        });
    }

    public function mockGroupLimit(array $allValues = [], string $firstValue = ''): GroupLimit
    {
        return GroupLimitMocks::mockGroupLimit($this, $allValues, $firstValue);
    }
}
