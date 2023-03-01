<?php
namespace Test\Feature\CleanRegex\_figures;

use PHPUnit\Framework\TestCase;
use Test\Utils\TestCase\TestCaseExactMessage;
use TRegx\CleanRegex\Exception\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    use TestCaseExactMessage;

    public function singleFigurePatterns(): array
    {
        return [
            'inject()' => [function (string $template) {
                return Pattern::inject($template, ['Valar morghulis']);
            }],

            'template.alteration()' => [function (string $template) {
                return Pattern::template($template)->alteration(['Joffrey', 'Cersei', 'Ilyn Payne', 'The Hound']);
            }],
            'template.literal()'    => [function (string $template) {
                return Pattern::template($template)->literal('Valar morghulis');
            }],
            'template.pattern()'    => [function (string $template) {
                return Pattern::template($template)->pattern('Valar morghulis');
            }],
            'template.mask()'       => [function (string $template) {
                return Pattern::template($template)->mask('*', ['*' => 'Bar']);
            }],

            'builder.alteration()' => [function (string $template) {
                return Pattern::builder($template)->alteration(['Joffrey', 'Cersei', 'Ilyn Payne', 'The Hound'])->build();
            }],
            'builder.literal()'    => [function (string $template) {
                return Pattern::builder($template)->literal('Valar morghulis')->build();
            }],
            'builder.pattern()'    => [function (string $template) {
                return Pattern::builder($template)->pattern('Valar morghulis')->build();
            }],
            'builder.mask()'       => [function (string $template) {
                return Pattern::builder($template)->mask('*', ['*' => 'Bar'])->build();
            }],
        ];
    }

    /**
     * @test
     * @dataProvider singleFigurePatterns
     */
    public function shouldThrowForSuperfluousFigures(callable $pattern)
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Supplied a superfluous figure. Used 0 placeholders, but 1 figures supplied.');
        // when
        $pattern('pattern');
    }

    /**
     * @test
     * @dataProvider singleFigurePatterns
     */
    public function shouldThrowForUnderflowFigures(callable $pattern)
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 2 placeholders, but 1 figures supplied.');
        // when
        $pattern('@@');
    }

    /**
     * @test
     * @dataProvider singleFigurePatterns
     */
    public function shouldThrowForUnderflowFiguresMany(callable $pattern)
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 5 placeholders, but 1 figures supplied.');
        // when
        $pattern('@@@@@');
    }

    public function twoFiguresPatterns(): array
    {
        return [
            'builder.alteration()' => [function (string $template) {
                return Pattern::builder($template)
                    ->alteration(['Joffrey', 'Cersei'])
                    ->alteration(['Ilyn Payne', 'The Hound'])
                    ->build();
            }],
            'builder.literal()'    => [function (string $template) {
                return Pattern::builder($template)
                    ->literal('Valar')
                    ->literal('morghulis')
                    ->build();
            }],
            'builder.mask()'       => [function (string $template) {
                return Pattern::builder($template)
                    ->mask('*', ['*' => 'Bar'])
                    ->mask('*', ['*' => 'Bar'])
                    ->build();
            }],
        ];
    }

    /**
     * @test
     * @dataProvider twoFiguresPatterns
     */
    public function shouldThrowForSuperfluousFigure(callable $pattern)
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Supplied a superfluous figure. Used 1 placeholders, but 2 figures supplied.');
        // when
        $pattern('@');
    }

    /**
     * @test
     * @dataProvider twoFiguresPatterns
     */
    public function shouldThrowForUnderflowFigure(callable $pattern)
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage('Not enough corresponding figures supplied. Used 3 placeholders, but 2 figures supplied.');
        // when
        $pattern('@@@');
    }
}
