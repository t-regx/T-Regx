<?php
namespace Test\Feature\CleanRegex\Match\flatMapAssoc;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrow_onNonArrayReturnType()
    {
        // given
        $pattern = Pattern::of("([A-Z])?[a-z']+")->match('Nice 1 matching 2 pattern');
        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid flatMapAssoc() callback return type. Expected array, but string ('string') given");
        // when
        $pattern->flatMapAssoc(Functions::constant('string'));
    }

    /**
     * @test
     */
    public function shouldFlatMapArrays()
    {
        // given
        $match = Pattern::of('Foo')->match('Foo');
        // when
        $result = $match->flatMapAssoc(Functions::constant(['a', 1 => ['b'], 2 => [['c']]]));
        // then
        $this->assertSame(['a', ['b'], [['c']]], $result);
    }
}
