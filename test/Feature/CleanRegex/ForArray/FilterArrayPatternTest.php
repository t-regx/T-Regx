<?php
namespace Test\Feature\TRegx\CleanRegex\ForArray;

use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\DataProvider\DataProviders as CrossDataProviders;

/**
 * @coversNothing
 */
class FilterArrayPatternTest extends TestCase
{
    /**
     * @test
     * @dataProvider filterMethods
     * @param string $method
     * @param null|int|array|callable|resource $listElement
     */
    public function test(string $method, $listElement)
    {
        // given
        $input = ['Foo', 1, $listElement];

        // when
        $output = pattern('')->forArray($input)->$method();

        // then
        $this->assertSame(['Foo'], $output);
    }

    public function filterMethods(): array
    {
        return CrossDataProviders::cross(
            [['filter'], ['filterAssoc']],
            DataProviders::allPhpTypes('string')
        );
    }
}
