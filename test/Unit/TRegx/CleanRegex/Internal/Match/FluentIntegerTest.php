<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Internal\Match\FluentInteger;

class FluentIntegerTest extends TestCase
{
    /**
     * @test
     */
    public function test_integer()
    {
        $this->assertSame(1, FluentInteger::parse(1));
    }

    /**
     * @test
     */
    public function test_string()
    {
        $this->assertSame(1, FluentInteger::parse('1'));
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedInteger()
    {
        // then
        $this->expectException(IntegerFormatException::class);
        $this->expectExceptionMessage("Expected to parse fluent element 'Foo', but it is not a valid integer");

        // when
        FluentInteger::parse('Foo');
    }

    /**
     * @test
     */
    public function shouldThrowForInvalidDataType()
    {
        // then
        $this->expectException(FluentMatchPatternException::class);
        $this->expectExceptionMessage("Invalid data types passed to `asInt()` method. Expected 'string' or 'int', but boolean (true) given");

        // when
        FluentInteger::parse(true);
    }
}
