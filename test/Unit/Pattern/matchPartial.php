<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\Pattern;
use Regex\RecursionException;
use Regex\UnicodeException;
use function Test\Fixture\Functions\catching;

class matchPartial extends TestCase
{
    /**
     * @test
     */
    public function first()
    {
        $word = new Pattern('\w+');
        $matcher = $word->matchPartial('Unbowed, Unbent, Unbroken');
        $this->assertSame('Unbowed', (string)$matcher->current());
    }

    /**
     * @test
     */
    public function next()
    {
        $word = new Pattern('\w+');
        $matcher = $word->matchPartial('Unbowed, Unbent, Unbroken');
        $matcher->next();
        $this->assertSame('Unbent', (string)$matcher->current());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $word = new Pattern('Valar Morghulis');
        $this->assertEmpty(\iterator_to_array($word->matchPartial('Valar Dohaeris')));
    }

    /**
     * @test
     */
    public function exhausted()
    {
        $word = new Pattern('\w+');
        $matcher = $word->matchPartial('Winter is coming');
        \iterator_to_array($matcher);
        catching(fn() => \iterator_to_array($matcher))
            ->assertException(\Exception::class)
            ->assertMessage('Cannot traverse an already closed generator');
    }

    /**
     * @test
     */
    public function encoding()
    {
        $pattern = new Pattern('word|\w+', 'u');
        $matcher = $pattern->matchPartial("word, word, \xc3\x28");
        catching(fn() => $matcher->current())
            ->assertException(UnicodeException::class)
            ->assertMessage('Malformed unicode subject.');
    }

    /**
     * @test
     */
    public function runtimeFailure()
    {
        $pattern = new Pattern('(*NO_JIT)(*LIMIT_RECURSION=3)first|((((fail))))');
        $matcher = $pattern->matchPartial('first, fail');
        $this->assertSame('first', (string)$matcher->current());
    }

    /**
     * @test
     */
    public function runtimeFailureNext()
    {
        $pattern = new Pattern('(*NO_JIT)(*LIMIT_RECURSION=3)first|((((fail))))');
        $matcher = $pattern->matchPartial('first, fail');
        catching(fn() => $matcher->next())
            ->assertException(RecursionException::class)
            ->assertMessage('Recursion depth limit exceeded when matching the subject.');
    }

    /**
     * @test
     */
    public function detail()
    {
        // given
        $word = new Pattern('(?<un>\w+)');
        $subject = 'Unbowed, Unbent, Unbroken';
        // when
        $matcher = $word->matchPartial($subject);
        /** @var Detail $detail */
        $detail = $matcher->current();
        // then
        $this->assertSame('Unbowed', $detail->text());
        $this->assertSame('Unbowed', $detail->group('un'));
        $this->assertSame(0, $detail->index());
        $this->assertSame(0, $detail->offset());
        $this->assertSame($subject, $detail->subject());
    }

    /**
     * @test
     */
    public function detailNext()
    {
        // given
        $word = new Pattern('(?<un>\w+)');
        // when
        $matcher = $word->matchPartial('Unbowed, Unbent, Unbroken');
        /** @var Detail $detail */
        $matcher->next();
        $detail = $matcher->current();
        // then
        $this->assertSame(1, $detail->index());
    }
}
