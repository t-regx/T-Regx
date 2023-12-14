<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use function Test\Fixture\Functions\catching;
use function Test\Fixture\Functions\systemErrorHandler;
use function Test\Fixture\Functions\systemWarning;

class _system extends TestCase
{
    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function ignoreWarning()
    {
        systemWarning(fn() => new Pattern('word'));
    }

    /**
     * @test
     */
    public function lackWarning()
    {
        // when
        catching(fn() => new Pattern('invalid)'));
        // then
        $this->assertNull(\error_get_last());
    }

    /**
     * @test
     */
    public function ignoreErrorHandler()
    {
        systemErrorHandler(function () {
            catching(fn() => new Pattern('+'))
                ->assertException(SyntaxException::class);
        });
    }
}
