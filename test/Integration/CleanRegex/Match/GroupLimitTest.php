<?php
namespace Test\Integration\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Match\Details\Group\DetailGroup;

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
        $this->assertEquals(['first', 'second'], $fromOnly);
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
        $this->assertEquals('first', $fromFirst);
    }

    /**
     * @test
     */
    public function shouldInvokeFirstConsumer()
    {
        // given
        $limit = GroupLimitFactory::groupLimitFirst($this, 'Foo Bar');

        // when
        $limit->first(function (DetailGroup $group) {
            // then
            $this->assertEquals('Foo Bar', $group->text());
        });
    }

    /**
     * @test
     */
    public function shouldFlatMapThrowException_forInvalidReturnType()
    {
        // given
        $limit = GroupLimitFactory::groupLimitAll($this, ['Foo Bar']);

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (4) given');

        // when
        $limit->flatMap(function (DetailGroup $group) {
            return 4;
        });
    }
}
