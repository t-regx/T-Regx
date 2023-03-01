<?php
namespace Test\Feature\CleanRegex\_figures\_partial;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use AssertsPattern;

    public function patterns(): array
    {
        return [
            'inject()' => [function (string $pattern, string $figure) {
                return Pattern::inject($pattern, [$figure]);
            }],

            'mask' => [function (string $pattern, string $figure) {
                return Pattern::template($pattern)->mask('*', ['*' => $figure]);
            }],

            'template.literal' => [function (string $pattern, string $figure) {
                return Pattern::template($pattern)->literal($figure);
            }],

            'builder.literal' => [function (string $pattern, string $figure) {
                return Pattern::builder($pattern)->literal($figure)->build();
            }],

            'builder.pattern' => [function (string $pattern, string $figure) {
                return Pattern::builder($pattern)->pattern($figure)->build();
            }],
        ];
    }

    /**
     * @test
     * @dataProvider patterns
     */
    public function shouldMatchOptionalPlaceholder(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^Foo:@?$', 'Bar');
        // when, then
        $this->assertTrue($pattern->test('Foo:Bar'), 'Failed to assert that placeholder was optional and present');
    }

    /**
     * @test
     * @dataProvider patterns
     */
    public function shouldMatchOptionalPlaceholderAbsent(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^Foo:@?$', 'Bar');
        // when, then
        $this->assertTrue($pattern->test('Foo:'), "Failed to assert that placeholder was optional and absent");
    }

    /**
     * @test
     * @dataProvider patterns
     */
    public function shouldNotMatchPartialOptionalPlaceholder(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^Foo:@?$', 'Bar');
        // when, then
        $this->assertTrue($pattern->fails('Foo:Ba'), "Failed to assert that partial of placeholder was matched");
    }

    /**
     * @test
     * @dataProvider patterns
     */
    public function shouldNotApplyQuantifierBeforeEmpty(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^Foo:@?$', '');
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }

    /**
     * @test
     * @dataProvider patterns
     */
    public function shouldNotApplyQuantifierBefore(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^Foo:@?$', 'Bar');
        // when, then
        $this->assertTrue($pattern->fails('Foo'), "Failed to assert that quantifier applied to placeholder");
    }

    /**
     * @test
     * @dataProvider patternsRegularExpressionFigure
     */
    public function shouldNotCorruptCommentInPattern(callable $patternWithFigure)
    {
        // given
        $pattern = $patternWithFigure('^@$', 'x', "value#comment@comment\n");
        // when, then
        $this->assertConsumesFirst('value', $pattern);
        $this->assertPatternIs("/^(?:value#comment@comment\n)$/x", $pattern);
    }

    public function patternsRegularExpressionFigure(): array
    {
        return [
            'template.pattern' => [function (string $template, string $modifiers, string $pattern) {
                return Pattern::template($template, $modifiers)->pattern($pattern);
            }],
            'template.mask'    => [function (string $template, string $modifiers, string $pattern) {
                return Pattern::template($template, $modifiers)->mask('*', ['*' => $pattern]);
            }],
            'builder.pattern'  => [function (string $template, string $modifiers, string $pattern) {
                return Pattern::builder($template, $modifiers)->pattern($pattern)->build();
            }],
            'builder.mask'     => [function (string $template, string $modifiers, string $pattern) {
                return Pattern::builder($template, $modifiers)->mask('*', ['*' => $pattern])->build();
            }],
        ];
    }
}
