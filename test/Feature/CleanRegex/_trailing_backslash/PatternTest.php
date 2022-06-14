<?php
namespace Test\Feature\CleanRegex\_trailing_backslash;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Exception\MaskMalformedPatternException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Pattern;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     * @dataProvider entryPoints
     * @param Pattern $pattern
     */
    public function shouldThrow_forTrailingBackslash(Pattern $pattern): void
    {
        // then
        $this->expectException(PatternMalformedPatternException::class);
        $this->expectExceptionMessage('Pattern may not end with a trailing backslash');

        // when
        $pattern->test('Foo');
    }

    public function entryPoints(): array
    {
        return [
            'Pattern::of()'                  => [Pattern::of('Foo \\')],
            'Pattern::of(,x)'                => [Pattern::of('Foo \\', 'x')],
            'Pattern::inject()'              => [Pattern::inject('Foo \\', [])],
            'Pattern::builder(,x)->build()'  => [Pattern::builder('cat\\', 'x')->build()],
            'Pattern::template()->literal()' => [Pattern::template('Foo @ \\')->literal('&')],
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
        Pattern::compose(['Foo & \\'])->testAny('Foo');
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
            'Pattern::mask()'             => [
                function () {
                    return Pattern::mask('Foo%', ['%' => '()\\']);
                },
                "Malformed pattern '()\' assigned to keyword '%'"
            ],
            'Pattern::template()->mask()' => [
                function () {
                    return Pattern::template('Foo @')->mask('w', ['w' => '\\']);
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
