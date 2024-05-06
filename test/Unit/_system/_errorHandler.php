<?php
namespace Test\Unit\_system;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\PcreException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\systemErrorHandler;

class _errorHandler extends TestCase
{
    private Pattern $pattern;

    /**
     * @before
     */
    public function pattern()
    {
        $this->pattern = new Pattern('(?=word\K)');
    }

    /**
     * @test
     */
    public function first()
    {
        systemErrorHandler(function (): void {
            catching(fn() => $this->pattern->first('word'))
                ->assertException(PcreException::class);
        });
    }

    /**
     * @test
     */
    public function search()
    {
        systemErrorHandler(function (): void {
            catching(fn() => $this->pattern->search('word'))
                ->assertException(PcreException::class);
        });
    }

    /**
     * @test
     */
    public function match()
    {
        systemErrorHandler(function (): void {
            catching(fn() => $this->pattern->match('word'))
                ->assertException(PcreException::class);
        });
    }
}
