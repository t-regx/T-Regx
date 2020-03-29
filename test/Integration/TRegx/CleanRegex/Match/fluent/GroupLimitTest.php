<?php
namespace Test\Integration\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use Test\Integration\TRegx\CleanRegex\Match\GroupLimitFactory;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnGroups(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, [['Foo', 1], ['Bar', 2]]);

        // when
        $chained = $limit->fluent();

        // then
        $chained
            ->map(function (MatchGroup $matchGroup) {
                $this->assertEquals($matchGroup->byteOffset() === 1 ? 'Foo' : 'Bar', $matchGroup->text());
            })
            ->all();
    }

    /**
     * @test
     */
    public function shouldThrow_first(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitFirstUnmatched($this);

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed is empty.');

        // when
        $chained = $limit->fluent();

        // then
        $chained->first();
    }

    /**
     * @test
     */
    public function shouldThrow_first_callback(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitFirstUnmatched($this);

        // then
        $this->expectException(NoSuchElementFluentException::class);
        $this->expectExceptionMessage('Expected to get the first element from fluent pattern, but the elements feed is empty.');

        // when
        $chained = $limit->fluent();

        // then
        $chained->first(function () {
            $this->fail("Failed to assert that first() callback is not called for an empty feed");
        });
    }

    /**
     * @test
     */
    public function shouldInvokeCallback_first(): void
    {
        // given
        $limit = GroupLimitFactory::groupLimitFirst($this, 'Foo');

        // when
        $chained = $limit->fluent();

        // then
        $chained->first(function (MatchGroup $matchGroup) {
            $this->assertEquals('Foo', $matchGroup->text());
        });
    }
}
