<?php
namespace Test\Feature\CleanRegex\filter;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

/**
 * @covers \TRegx\CleanRegex\Pattern::filter
 */
class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFilterSequentialArray()
    {
        // when
        $filtered = Pattern::of('Bar')->filter(['Foo', 'Bar']);
        // then
        $this->assertSame(['Bar'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterAssociativeArray()
    {
        // when
        $filtered = Pattern::of('^[aoe]$')->filter(['a' => 'a', 'b' => 'b', 'o' => 'o']);
        // then
        $this->assertSame(['a', 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_forEmpty()
    {
        // when
        $filtered = Pattern::of('Fail')->filter(['One', 'Two', 'Three']);
        // then
        $this->assertSame([], $filtered);
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
        $pattern->filter(['Foo', $item]);
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
        $pattern->filter(['value']);
    }
}
