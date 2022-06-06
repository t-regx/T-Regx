<?php
namespace Test\Feature\CleanRegex\ForArray;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\CleanRegex\Pattern;
use TRegx\DataProvider\CrossDataProviders;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Pattern::forArray
 * @covers \TRegx\CleanRegex\ForArray\ForArrayPattern
 */
class ForArrayPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFilter_forSequentialArray()
    {
        // when
        $actual = pattern('Bar')->forArray(['Foo', 'Bar'])->filter();
        // then
        $this->assertSame(['Bar'], $actual);
    }

    /**
     * @test
     */
    public function shouldFilter_forAssociativeArray()
    {
        // when
        $filtered = Pattern::of('^[aoe]$')->forArray(['a' => 'a', 'b' => 'b', 'o' => 'o'])->filter();
        // then
        $this->assertSame(['a', 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterAssoc_forSequentialArray()
    {
        // when
        $actual = pattern('Bar')->forArray(['Foo', 'Bar'])->filterAssoc();
        // then
        $this->assertSame([1 => 'Bar'], $actual);
    }

    /**
     * @test
     */
    public function shouldFilterAssoc_forAssociativeArray()
    {
        // when
        $filtered = Pattern::of('^[aoe]$')->forArray(['a' => 'a', 'b' => 'b', 'o' => 'o'])->filterAssoc();
        // then
        $this->assertSame(['a' => 'a', 'o' => 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_forEmpty()
    {
        // when
        $filtered = Pattern::of('Fail')->forArray(['One', 'Two', 'Three'])->filter();
        // then
        $this->assertSame([], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterAssoc_forEmpty()
    {
        // when
        $filtered = Pattern::of('Fail')->forArray(['One', 'Two', 'Three'])->filterAssoc();
        // then
        $this->assertSame([], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterByKeys()
    {
        // when
        $actual = pattern('Bar')->forArray(['Foo' => 0, 'Bar' => 1])->filterByKeys();
        // then
        $this->assertSame(['Bar' => 1], $actual);
    }

    /**
     * @test
     * @dataProvider filterMethods
     * @param string $method
     * @param null|int|array|callable|resource $listElement
     * @param string $type
     */
    public function shouldThrowOnInvalidType(string $method, $listElement, string $type)
    {
        // given
        $filterArray = Pattern::of('')->forArray(['Foo', $listElement]);
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Only elements of type 'string' can be filtered, but $type given");
        // when
        $filterArray->$method();
    }

    public function filterMethods(): array
    {
        $names = [['filter'], ['filterAssoc']];
        $types = DataProviders::allPhpTypes('string');

        return CrossDataProviders::cross($names, $types);
    }
}
