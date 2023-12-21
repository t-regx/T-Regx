<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _trailingBackslash extends TestCase
{
    /**
     * @dataProvider patterns
     */
    public function test(string $pattern)
    {
        catching(fn() => new Pattern($pattern))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('Trailing backslash in regular expression');
    }

    public function patterns(): DataProvider
    {
        return DataProvider::list('\\', '\\\\\\', '\\\\\\\\\\', '\c\\', '\Q\\');
    }

    /**
     * @test
     * @dataProvider escapedPatterns
     * @doesNotPerformAssertions
     */
    public function allowed(string $pattern)
    {
        new Pattern($pattern);
    }

    public function escapedPatterns(): DataProvider
    {
        return DataProvider::list(
            '\\\\',
            '\\\\\\\\',
            '\Q\\\\',
            '\w',
            '\d'
        );
    }

    /**
     * @test
     */
    public function control()
    {
        // when
        $call = catching(fn() => new Pattern('\c\\\\'));
        // then
        $call
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('\ at end of pattern');
    }
}
