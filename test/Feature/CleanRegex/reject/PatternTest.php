<?php
namespace Test\Feature\CleanRegex\reject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Pattern::reject
 */
class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldRejectFromSequentialArray()
    {
        // when
        $filtered = Pattern::of('Bar')->reject(['Foo', 'Bar', 'Cat']);
        // then
        $this->assertSame(['Foo', 'Cat'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterAssociativeArray()
    {
        // when
        $filtered = Pattern::of('^[bc]$')->reject([
            'a' => 'a',
            'b' => 'b',
            'c' => 'c',
            'o' => 'o'
        ]);
        // then
        $this->assertSame(['a', 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldRejectAll()
    {
        // when
        $filtered = Pattern::of('.*')->reject(['One', 'Two', 'Three']);
        // then
        $this->assertSame([], $filtered);
    }

    /**
     * @test
     */
    public function shouldRejectNone()
    {
        // when
        $filtered = Pattern::of('Fail')->reject(['One', 'Two', 'Three']);
        // then
        $this->assertSame(['One', 'Two', 'Three'], $filtered);
    }

    /**
     * @test
     * @dataProvider invalidDataTypes
     * @param null|int|array|callable|resource $item
     * @param string $type
     */
    public function shouldThrowOnInvalidType($item, string $type)
    {
        // given
        $pattern = Pattern::of('');
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Expected an array of elements of type 'string' to be filtered, but $type given");
        // when
        $pattern->reject(['Foo', $item]);
    }

    public function invalidDataTypes(): array
    {
        return DataProviders::allPhpTypes('string');
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $pattern = Pattern::of('+');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $pattern->reject(['value']);
    }
}
