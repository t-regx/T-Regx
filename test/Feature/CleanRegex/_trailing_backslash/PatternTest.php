<?php
namespace Test\Feature\TRegx\CleanRegex\_trailing_backslash;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;

/**
 * @coversNothing
 */
class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider entryPoints
     * @param callable $entryPoint
     */
    public function shouldThrow_forTrailingBackslash(callable $entryPoint): void
    {
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $entryPoint()->test('');
    }

    public function entryPoints(): array
    {
        return [
            'Pattern::of()'                           => [function () {
                return Pattern::of('Foo \\');
            }],
            'Pattern::of(,x)'                         => [function () {
                return Pattern::of('Foo \\', 'x');
            }],
            'Pattern::inject()'                       => [function () {
                return Pattern::inject('Foo \\', []);
            }],
            'Pattern::template(,x)->build()'          => [function () {
                return Pattern::template('cat\\', 'x')->build();
            }],
            'Pattern::template()->literal()->build()' => [function () {
                return Pattern::template('Foo @ \\')->literal('&')->build();
            }],
        ];
    }

    /**
     * @test
     */
    public function shouldThrow_forTrailingBackslash_compose(): void
    {
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        Pattern::compose(['Foo & \\'])->anyMatches('Foo');
    }

    /**
     * @test
     * @dataProvider templateEntryPoints
     * @param callable $entryPoint
     * @param string $message
     */
    public function shouldThrow_template_forTrailingBackslash(callable $entryPoint, string $message): void
    {
        // then
        $this->expectException(MaskMalformedPatternException::class);
        $this->expectExceptionMessage($message);

        // when
        $entryPoint();
    }

    public function templateEntryPoints(): array
    {
        return [
            'Pattern::mask()'                      => [
                function () {
                    return Pattern::mask('Foo%', ['%' => '()\\']);
                },
                "Malformed pattern '()\' assigned to keyword '%'"
            ],
            'Pattern::template()->mask()->build()' => [
                function () {
                    return Pattern::template('Foo @')->mask('w', ['w' => '\\'])->build();
                },
                "Malformed pattern '\' assigned to keyword 'w'"
            ],
        ];
    }

    /**
     * @test
     */
    public function shouldAcceptEscapedTrailingBashslash()
    {
        // when
        $pattern = Pattern::of('foo\\\\');

        // then
        $this->assertConsumesFirst('foo\\', $pattern);
    }
}
