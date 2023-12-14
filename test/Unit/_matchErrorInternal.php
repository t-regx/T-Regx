<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\PcreException;
use function Test\Fixture\Functions\catching;

class _matchErrorInternal extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern(): void
    {
        $this->pattern = new Pattern('(?=word\K)');
    }

    protected function tearDown(): void
    {
        \error_clear_last();
    }

    /**
     * @test
     */
    public function test()
    {
        $this->assertInternalError(fn() => $this->pattern->test('word'));
    }

    /**
     * @test
     */
    public function count_()
    {
        $this->assertInternalError(fn() => $this->pattern->count('word'));
    }

    /**
     * @test
     */
    public function replace()
    {
        $this->assertInternalError(fn() => $this->pattern->replace('word', ''));
    }

    /**
     * @test
     */
    public function replaceCallback()
    {
        $this->assertInternalError(fn() => $this->pattern->replaceCallback('word', fn() => null));
    }

    /**
     * @test
     */
    public function split()
    {
        $this->assertInternalError(fn() => $this->pattern->split('word'));
    }

    /**
     * @test
     */
    public function filter()
    {
        $this->assertInternalError(fn() => $this->pattern->filter(['word']));
    }

    /**
     * @test
     */
    public function reject()
    {
        $this->assertInternalError(fn() => $this->pattern->reject(['word']));
    }

    private function assertInternalError(callable $block): void
    {
        catching($block)
            ->assertException(PcreException::class)
            ->assertMessage('Failed to match the subject, due to pcre internal error.');
    }
}
