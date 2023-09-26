<?php
namespace Test\Unit\_nonPrintable;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\catching;

class groupExists extends TestCase
{
    use NonPrintables;

    /**
     * @dataProvider nonPrintables
     */
    public function test(string $nonPrintable, string $expected)
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists($nonPrintable))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessageEndsWith("'$expected'.");
    }

    /**
     * @test
     */
    public function malformedUnicodeNoLastError()
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists("\xc3\x28"))
            ->assertException(\InvalidArgumentException::class);
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }
}
