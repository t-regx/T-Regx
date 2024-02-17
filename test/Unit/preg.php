<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\ExecutionException;
use Regex\PregPattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;

class preg extends TestCase
{
    /**
     * @test
     */
    public function empty()
    {
        catching(fn() => new PregPattern(''))
            ->assertException(ExecutionException::class)
            ->assertMessage('Empty regular expression.');
    }

    /**
     * @test
     */
    public function predicate()
    {
        $pattern = new PregPattern('#\w+#');
        $this->assertTrue($pattern->test('word'));
    }

    /**
     * @test
     */
    public function predicateFalse()
    {
        $pattern = new PregPattern('/\d+/');
        $this->assertFalse($pattern->test('word'));
    }

    /**
     * @test
     */
    public function search()
    {
        $pattern = new PregPattern('/\w+/');
        $this->assertSame(['Valar', 'Morghulis'], $pattern->search('Valar Morghulis'));
    }

    /**
     * @test
     */
    public function syntaxError()
    {
        catching(fn() => new PregPattern('/+/'))
            ->assertException(SyntaxException::class);
    }

    /**
     * @test
     */
    public function modifiers()
    {
        $pattern = new PregPattern('/^[a-z]+$/im');
        $this->assertSame(['Valar', 'morghulis'], $pattern->search("Valar\nmorghulis"));
    }

    /**
     * @test
     */
    public function unclosed()
    {
        $this->markAsRisky();
        catching(fn() => new PregPattern('/pattern'))
            ->assertException(ExecutionException::class)
            ->assertMessage("No ending delimiter '/' found.");
    }

    /**
     * @test
     */
    public function escapedDelimiter()
    {
        catching(fn() => new PregPattern('/foo\/bar/'))
            ->assertExceptionNone();
    }

    /**
     * @test
     */
    public function delimitedIdentity()
    {
        $pattern = new PregPattern('<Winter is coming>i');
        $this->assertSame('<Winter is coming>i', $pattern->delimited());
    }
}
