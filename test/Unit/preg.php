<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\BacktrackException;
use Regex\ExecutionException;
use Regex\PregPattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\since;

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
    public function syntaxErrorPosition()
    {
        /** @var SyntaxException $exception */
        $exception = catching(fn() => new PregPattern('  /foo )/  '))->get();
        $this->assertSame(4, $exception->syntaxErrorByteOffset);
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

    /**
     * @test
     */
    public function explicitCapture()
    {
        // when
        $call = catching(fn() => new PregPattern('/foo/n'));
        // then
        if (since('8.2.0')) {
            $call->assertExceptionNone();
        } else {
            $call
                ->assertException(ExecutionException::class)
                ->assertMessage("Unknown modifier 'n'.");
        }
    }

    /**
     * @test
     */
    public function predicateBacktrack()
    {
        $pattern = new PregPattern('#(\d+\d+)+3#');
        catching(fn() => $pattern->test('11111111111111111111 3'))
            ->assertException(BacktrackException::class);
    }

    /**
     * @test
     */
    public function searchBacktrack()
    {
        $pattern = new PregPattern('#(\d+\d+)+3#');
        catching(fn() => $pattern->search('11111111111111111111 3'))
            ->assertException(BacktrackException::class);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function noSecondCall()
    {
        $pattern = new PregPattern('%(\d+\d+)+3%');
        $pattern->test('123 11111111111111111111 3');
    }

    /**
     * @test
     */
    public function groupNames()
    {
        $pattern = new PregPattern('%(?<Valar>) (Morghulis)%');
        $this->assertSame(['Valar', null], $pattern->groupNames());
    }

    /**
     * @test
     */
    public function groupCount()
    {
        $pattern = new PregPattern('%(?<Valar>) (Morghulis) ()%');
        $this->assertSame(3, $pattern->groupCount());
    }
}
