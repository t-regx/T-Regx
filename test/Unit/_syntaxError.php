<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _syntaxError extends TestCase
{
    /**
     * @dataProvider malformedPatterns
     */
    public function test(string $pattern)
    {
        catching(fn() => new Pattern($pattern))
            ->assertException(SyntaxException::class);
    }

    public function malformedPatterns(): DataProvider
    {
        return DataProvider::list(')', '+', '(?<>)', '[[:invalid:]]');
    }
}
