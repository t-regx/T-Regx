<?php
namespace Test\Interaction\TRegx\CleanRegex\Internal\Prepared\alternation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\DataProvider\DataProviders;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider alternation_empty
     * @param Parser $parser
     */
    public function test_alternation_empty(Parser $parser)
    {
        // when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('/Either 5\/6 or (?:) :)/', $pattern->delimited());
    }

    public function alternation_empty(): array
    {
        return [
            'bind @'   => [new BindingParser('Either @one or @two :)', ['one' => '5/6', 'two' => []], new NoTemplate())],
            'inject #' => [new InjectParser('Either @ or @ :)', ['5/6', []], new NoTemplate())],
        ];
    }

    /**
     * @test
     * @dataProvider alternation_triple
     * @param Parser $parser
     */
    public function test_alternation_triple(Parser $parser)
    {
        // when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('/Either 5\/6 or (?:6\/7|7\/8|8\/9) :)/', $pattern->delimited());
    }

    public function alternation_triple(): array
    {
        return [
            'bind @'   => [new BindingParser('Either @one or @two :)', ['one' => '5/6', 'two' => ['6/7', '7/8', '8/9']], new NoTemplate())],
            'inject #' => [new InjectParser('Either @ or @ :)', ['5/6', ['6/7', '7/8', '8/9']], new NoTemplate())],
        ];
    }

    /**
     * @test
     * @dataProvider delimiters
     * @param Parser $parser
     */
    public function test_alternation_quote_delimiters(Parser $parser)
    {
        // when
        $pattern = PrepareFacade::build($parser, false, '');

        // then
        $this->assertSame('%Either /# (?:5\%) :)%', $pattern->delimited());
    }

    public function delimiters(): array
    {
        return [
            'bind @'   => [new BindingParser('Either /# @one :)', ['one' => ['5%']], new NoTemplate())],
            'inject #' => [new InjectParser('Either /# @ :)', [['5%']], new NoTemplate())],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs_arrays
     * @param Parser $parser
     */
    public function shouldThrow_onInvalidInput_array(Parser $parser)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound alternate value. Expected string, but array (0) given");

        // when
        PrepareFacade::build($parser, false, '');
    }

    public function invalidInputs_arrays(): array
    {
        return [
            'bind @'   => [new BindingParser('@a@b@c', ['a' => '', 'b' => '', 'c' => ['', []]], new NoTemplate())],
            'inject #' => [new InjectParser('@@@', ['', '', ['', []]], new NoTemplate())],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs_integers
     * @param Parser $parser
     */
    public function shouldThrow_onInvalidInput_integer(Parser $parser)
    {
        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound alternate value. Expected string, but integer (4) given");

        // when
        PrepareFacade::build($parser, false, '');
    }

    /**
     * @test
     * @dataProvider flagsAndAlternationResults
     * @param Parser $parser
     * @param string $flags
     * @param string $expected
     */
    public function shouldRemoveAlternationDuplicatesBasedOnFlags(Parser $parser, string $flags, string $expected)
    {
        // when
        $pattern = PrepareFacade::build($parser, false, $flags);

        // then
        $this->assertSame("/(?:$expected)/$flags", $pattern->delimited());
    }

    public function flagsAndAlternationResults(): array
    {
        $values = ['Foo', 'foo', 'łóżko', 'ŁÓŻKO'];
        return DataProviders::builder()
            ->addSection(
                new BindingParser('@a', ['a' => $values], new NoTemplate()),
                new InjectParser('@', [$values], new NoTemplate())
            )
            ->addJoinedSection(
                ['i', 'Foo|łóżko|ŁÓŻKO'],
                ['ui', 'Foo|łóżko']
            )
            ->entryKeyMapper(function (array $keys) {
                [$parser, $flags] = $keys;
                return ['bind', 'inject', 'prepared'][$parser] . ', ' . ['i', 'ui'][$flags];
            })
            ->build();
    }

    public function invalidInputs_integers(): array
    {
        return [
            'bind @'   => [new BindingParser('@a@b@c', ['a' => '', 'b' => '', 'c' => ['', 4]], new NoTemplate())],
            'inject #' => [new InjectParser('@@@', ['', '', ['', 4]], new NoTemplate())],
        ];
    }
}
