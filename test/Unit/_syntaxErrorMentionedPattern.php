<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;

class _syntaxErrorMentionedPattern extends TestCase
{
    public function test()
    {
        $this->assertExceptionMessage('\w+(?<>)', "Subpattern name expected, near position 6.

'\w+(?<>)'");
    }

    /**
     * @test
     */
    public function apostrophe()
    {
        $this->assertExceptionMessage("\w+'(?<>)'", <<<EXCEPTION
Subpattern name expected, near position 7.

"\w+'(?<>)'"
EXCEPTION
        );
    }

    /**
     * @test
     */
    public function apostropheFirst()
    {
        $this->assertExceptionMessage("'\w+'(?n)+", <<<EXCEPTION
Quantifier does not follow a repeatable item, near position 9.

"'\w+'(?n)+"
EXCEPTION
        );
    }

    private function assertExceptionMessage(string $pattern, string $message): void
    {
        catching(fn() => new Pattern($pattern))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith($message);
    }
}
