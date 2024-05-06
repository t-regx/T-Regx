<?php
namespace Test\Unit\_groupIdentifier;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _index extends TestCase
{
    /**
     * @dataProvider negativeIndices
     */
    public function test(int $index)
    {
        $pattern = new Pattern('Valar');
        catching(fn() => $pattern->groupExists($index))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group index must be a non-negative integer, given: $index.");
    }

    public function negativeIndices(): DataProvider
    {
        return DataProvider::list(-1, -2);
    }
}
