<?php
namespace Test\Feature\TRegx\CleanRegex\ForArray;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\DataProvider\CrossDataProviders;
use function pattern;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFilter()
    {
        // when
        $actual = pattern('Bar')->forArray(['Foo', 'Bar'])->filter();

        // then
        $this->assertSame(['Bar'], $actual);
    }

    /**
     * @test
     */
    public function shouldFilterAssoc()
    {
        // when
        $actual = pattern('Bar')->forArray(['Foo', 'Bar'])->filterAssoc();

        // then
        $this->assertSame([1 => 'Bar'], $actual);
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
        $input = ['Foo', $listElement];

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Only elements of type 'string' can be filtered, but $type given");

        // when
        pattern('')->forArray($input)->$method();
    }

    public function filterMethods(): array
    {
        return CrossDataProviders::cross(
            [['filter'], ['filterAssoc']],
            DataProviders::allPhpTypes('string')
        );
    }
}
