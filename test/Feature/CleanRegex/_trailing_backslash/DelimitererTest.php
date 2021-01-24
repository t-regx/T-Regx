<?php
namespace Test\Feature\TRegx\CleanRegex\_trailing_backslash;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\Exception\MalformedPatternException;

class DelimitererTest extends TestCase
{
    /**
     * @test
     * @dataProvider entryPoints
     * @param callable $entryPoint
     */
    public function shouldThrow_forTrailingBackslash(callable $entryPoint): void
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $entryPoint();
    }

    public function entryPoints(): array
    {
        return [
            [function () {
                return Pattern::of('Foo \\');
            }],
            [function () {
                return Pattern::prepare(['Foo \\']);
            }],
            [function () {
                return Pattern::inject('Foo \\', []);
            }],
            [function () {
                return Pattern::format('Foo%', ['%' => '()\\']);
            }],
            [function () {
                return Pattern::template('Foo & \\')->formatting('duper', ['u' => '.*'])->build();
            }],
            [function () {
                return Pattern::template('Foo & \\')->format('duper', ['u' => '.*']);
            }],
        ];
    }
}
