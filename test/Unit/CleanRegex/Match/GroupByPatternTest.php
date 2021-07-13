<?php
namespace Test\Unit\TRegx\CleanRegex\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use Test\Utils\ThrowsForUnmockedMethods;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\Model\Matches\RawMatchesOffset;
use TRegx\CleanRegex\Match\GroupByPattern;

/**
 * @covers \TRegx\CleanRegex\Match\GroupByPattern
 */
class GroupByPatternTest extends TestCase
{
    use ThrowsForUnmockedMethods;

    /**
     * @test
     */
    public function shouldThrow_forFlatMap_forInvalidReturnType()
    {
        // given
        $pattern = new GroupByPattern($this->base(), 'victim');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMap() callback return type. Expected array, but integer (4) given');

        // when
        $pattern->flatMap(Functions::constant(4));
    }

    /**
     * @test
     */
    public function shouldThrow_forFlatMapAssoc_forInvalidReturnType()
    {
        // given
        $pattern = new GroupByPattern($this->base(), 'victim');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage('Invalid flatMapAssoc() callback return type. Expected array, but integer (4) given');

        // when
        $pattern->flatMapAssoc(Functions::constant(4));
    }

    public function base(): Base
    {
        $base = $this->createMock(Base::class);
        $base->expects($this->once())->method('getUserData')->willReturn(new UserData());
        $base->expects($this->once())->method('matchAllOffsets')->willReturn(new RawMatchesOffset([
            0        => [['Joffrey', 1]],
            'victim' => [['Joffrey', 1]],
        ]));
        return $base;
    }
}
