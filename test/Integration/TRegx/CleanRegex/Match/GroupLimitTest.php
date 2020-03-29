<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnValues_all(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo', 1], ['Bar', 2]]);

        // when
        $result = $limit->all();

        // then
        $this->assertEquals(['Foo', 'Bar'], $result);
    }

    /**
     * @test
     */
    public function shouldReturnValues_only(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['first', 0], ['second', 1], ['third', 2]]);

        // when
        $fromOnly = $limit->only(2);

        // then
        $this->assertEquals($fromOnly, ['first', 'second']);
    }

    /**
     * @test
     */
    public function shouldReturnValue_first(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitFirst($this, 'first');

        // when
        $fromFirst = $limit->first();

        // then
        $this->assertEquals($fromFirst, 'first');
    }

    /**
     * @test
     */
    public function shouldReturnValues_iterator(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo', 1], ['Bar', 2]]);

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
        $limit = GroupLimitFactory::groupLimitFirst($this, 'Foo Bar');

        // when
        $limit->first(function (MatchGroup $group) {
            // then
            $this->assertEquals('Foo Bar', $group->text());
        });
    }
}
