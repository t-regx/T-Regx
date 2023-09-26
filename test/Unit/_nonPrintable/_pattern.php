<?php
namespace Test\Unit\_nonPrintable;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;

class _pattern extends TestCase
{
    use NonPrintables;

    /**
     * @dataProvider nonPrintables
     */
    public function test(string $nonPrintable, string $expected)
    {
        catching(fn() => new Pattern("+invalid $nonPrintable"))
            ->assertException(SyntaxException::class)
            ->assertMessageContains("'+invalid $expected'")
            ->assertMessageEndsWith('(contains non-printable characters)');
    }

    /**
     * @test
     */
    public function malformedUnicodeNoLastError()
    {
        catching(fn() => new Pattern("\xc3\x28"))
            ->assertException(SyntaxException::class);
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }

    /**
     * @test
     */
    public function spacePrintable()
    {
        catching(fn() => new Pattern('+pattern space'))
            ->assertException(SyntaxException::class)
            ->assertMessageEndsWith("'+pattern space'");
    }
}
