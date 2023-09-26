<?php
namespace Test\Unit\_nonPrintable;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

class _nonPrintable extends TestCase
{
    public function test()
    {
        catching(fn() => new Pattern("+\w\r\1"))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('Quantifier does not follow a repeatable item, near position 0.')
            ->assertMessageContains("'+\w \1'")
            ->assertMessageEndsWith('(contains non-printable characters)');
    }

    /**
     * @test
     */
    public function nullByte()
    {
        // when
        $call = catching(fn() => new Pattern("\w\0"));
        // then
        if (since('8.2.0')) {
            $call
                ->assertExceptionNone();
        } else {
            $call
                ->assertException(SyntaxException::class)
                ->assertMessageStartsWith('Null byte in regex, near position 2.')
                ->assertMessageContains("'\w '")
                ->assertMessageEndsWith('(contains non-printable characters)');
        }
    }
}
