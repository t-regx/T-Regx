<?php
namespace Test\Feature\TRegx\CleanRegex;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Test\DataProviders;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Internal\FilteredArray
 */
class FilteredArrayTest extends TestCase
{
    /**
     * @test
     */
    public function shouldFilter()
    {
        // when
        $filtered = Pattern::of('^[aoe]$')->forArray(['a' => 'a', 'b' => 'b', 'o' => 'o'])->filter();
        // then
        $this->assertSame(['a', 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterAssoc()
    {
        // when
        $filtered = Pattern::of('^[aoe]$')->forArray(['a' => 'a', 'b' => 'b', 'o' => 'o'])->filterAssoc();
        // then
        $this->assertSame(['a' => 'a', 'o' => 'o'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilterEmpty()
    {
        // when
        $filtered = Pattern::of('Fail')->forArray(['One', 'Two', 'Three'])->filterAssoc();
        // then
        $this->assertSame([], $filtered);
    }

    /**
     * @test
     * @dataProvider phpTypes
     * @param null|int|array|callable|resource $listElement
     * @param string $type
     */
    public function shouldThrowOnInvalidArgument($listElement, string $type)
    {
        // given
        $filterArray = Pattern::of('')->forArray(['Foo', $listElement]);
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Only elements of type 'string' can be filtered, but $type given");
        // when
        $filterArray->filter();
    }

    public function phpTypes(): array
    {
        return DataProviders::allPhpTypes('string');
    }
}
