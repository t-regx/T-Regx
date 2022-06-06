<?php
namespace Test\Unit\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use Test\Utils\ExactExceptionMessage;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Nested;

/**
 * @covers \TRegx\CleanRegex\Internal\Match\FlatFunction
 */
class FlatFunctionTest extends TestCase
{
    use ExactExceptionMessage;

    /**
     * @test
     */
    public function shouldApply()
    {
        // given
        $function = new FlatFunction(Functions::letters(), '');

        // when
        $result = $function->apply('foo');

        // then
        $this->assertSame(['f', 'o', 'o'], $result);
    }

    /**
     * @test
     */
    public function shouldThrowForNonArray()
    {
        // given
        $function = new FlatFunction(Functions::identity(), 'bar');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid bar() callback return type. Expected array, but string ('foo') given");

        // when
        $function->apply('foo');
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $function = new FlatFunction(Functions::letters(), '');

        // when
        $result = $function->map(['foo', 'bar']);

        // then
        $this->assertEquals(new Nested([['f', 'o', 'o'], ['b', 'a', 'r']]), $result);
    }

    /**
     * @test
     */
    public function shouldMapThrowForNonArray()
    {
        // given
        $function = new FlatFunction(Functions::identity(), 'foo');

        // then
        $this->expectException(InvalidReturnValueException::class);
        $this->expectExceptionMessage("Invalid foo() callback return type. Expected array, but string ('cat') given");

        // when
        $function->map([['bar'], 'cat']);
    }
}
