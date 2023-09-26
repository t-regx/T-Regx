<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;

class _systemEncoding extends TestCase
{
    public function setUp(): void
    {
        \mb_internal_encoding('7bit');
    }

    public function tearDown(): void
    {
        \mb_internal_encoding('UTF-8');
    }

    /**
     * @test
     */
    public function detailOffset()
    {
        $pattern = new Pattern('F.r.', 'u');
        $detail = $pattern->first('Óurs is the Füry!');
        $this->assertSame(12, $detail->offset());
    }

    /**
     * @test
     */
    public function syntaxException()
    {
        catching(fn() => new Pattern('€+(?n)+', 'u'))
            ->assertException(SyntaxException::class)
            ->assertMessage("Quantifier does not follow a repeatable item, near position 8.

'€+(?n)+'
       ^");
    }
}
