<?php
namespace Test\Integration\TRegx\CleanRegex\Match\fluent;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\NoFirstElementFluentException;
use TRegx\CleanRegex\Match\Details\Group\MatchGroup;
use TRegx\CleanRegex\Match\GroupLimit;

class GroupLimitTest extends TestCase
{
    /**
     * @test
     */
    public function shouldReturnGroups(): void
    {
        // given
        /** @var $limit GroupLimit */
        [$limit] = $this->mockGroupLimit([['Foo', 1], ['Bar', 2]]);

        // when
        $chained = $limit->fluent();

        // then
        $chained->map(function (MatchGroup $matchGroup) {
            $this->assertEquals($matchGroup->byteOffset() === 1 ? 'Foo' : 'Bar', $matchGroup->text());
        });
    }

    /**
     * @test
     */
    public function shouldThrow_first(): void
    {
        // given
        /** @var $limit GroupLimit */
        [$limit] = $this->mockGroupLimit([]);

        // then
        $this->expectException(NoFirstElementFluentException::class);
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
        /** @var $limit GroupLimit */
        [$limit] = $this->mockGroupLimit([]);

        // then
        $this->expectException(NoFirstElementFluentException::class);

        // when
        $chained = $limit->fluent();

        // then
        $chained->first(function () {
            $this->fail();
        });
    }

    /**
     * @test
     */
    public function shouldInvokeCallback_first(): void
    {
        // given
        /** @var $limit GroupLimit */
        [$limit] = $this->mockGroupLimit([['Foo', 1], ['Bar', 2]]);

        // when
        $chained = $limit->fluent();

        // then
        $chained->first(function (MatchGroup $matchGroup) {
            $this->assertEquals('Foo', $matchGroup->text());
        });
    }

    private function mockGroupLimit(array $allValues = []): array
    {
        return \Test\Integration\TRegx\CleanRegex\Match\GroupLimitTest::mockGroupLimit($allValues);
    }
}
