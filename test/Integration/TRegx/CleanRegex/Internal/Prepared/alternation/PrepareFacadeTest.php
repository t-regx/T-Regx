<?php
namespace Test\Integration\TRegx\CleanRegex\Internal\Prepared\alternation;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\Parser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;

class PrepareFacadeTest extends TestCase
{
    /**
     * @test
     * @dataProvider alternation_empty
     * @param Parser $parser
     */
    public function test_alternation_empty(Parser $parser)
    {
        // given + when
        $pattern = (new PrepareFacade($parser, false))->getPattern();

        // then
        $this->assertEquals('/Either 5\/6 or  :)/', $pattern);
    }

    public function alternation_empty(): array
    {
        return [
            'bind @' => [new BindingParser('Either @one or @two :)', ['one' => '5/6', 'two' => []])],
            'inject #' => [new InjectParser('Either @ or @ :)', ['5/6', []])],
            'prepare []' => [new PreparedParser(['Either ', ['5/6'], ' or ', [[]], ' :)'])],
        ];
    }

    /**
     * @test
     * @dataProvider alternation_triple
     * @param Parser $parser
     */
    public function test_alternation_triple(Parser $parser)
    {
        // given + when
        $pattern = (new PrepareFacade($parser, false))->getPattern();

        // then
        $this->assertEquals('/Either 5\/6 or 6\/7|7\/8|8\/9 :)/', $pattern);
    }

    public function alternation_triple(): array
    {
        return [
            'bind @' => [new BindingParser('Either @one or @two :)', ['one' => '5/6', 'two' => ['6/7', '7/8', '8/9']])],
            'inject #' => [new InjectParser('Either @ or @ :)', ['5/6', ['6/7', '7/8', '8/9']])],
            'prepare []' => [new PreparedParser(['Either ', ['5/6'], ' or ', [['6/7', '7/8', '8/9']], ' :)'])],
        ];
    }

    /**
     * @test
     * @dataProvider delimiters
     * @param Parser $parser
     */
    public function test_alternation_quote_delimiters(Parser $parser)
    {
        // given + when
        $pattern = (new PrepareFacade($parser, false))->getPattern();

        // then
        $this->assertEquals('%Either /# 5\% :)%', $pattern);
    }

    public function delimiters(): array
    {
        return [
            'bind @' => [new BindingParser('Either /# @one :)', ['one' => ['5%']])],
            'inject #' => [new InjectParser('Either /# @ :)', [['5%']])],
            'prepare []' => [new PreparedParser(['Either /# ', [['5%']], ' :)'])],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs_arrays
     * @param Parser $parser
     */
    public function shouldThrow_onInvalidInput_array(Parser $parser)
    {
        // given
        $facade = new PrepareFacade($parser, false);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound value for alternating key X. Expected string, but array (0) given");

        // when
        $facade->getPattern();
    }

    public function invalidInputs_arrays(): array
    {
        return [
            'bind @' => [new BindingParser('@a@b@c', ['a' => '', 'b' => '', 'c' => ['', []]])],
            'inject #' => [new InjectParser('@@@', ['', '', 'foo' => ['', []]])],
            'prepared []' => [new InjectParser('@@@', ['', '', 'foo' => ['', []]])],
        ];
    }

    /**
     * @test
     * @dataProvider invalidInputs_integers
     * @param Parser $parser
     */
    public function shouldThrow_onInvalidInput_integer(Parser $parser)
    {
        // given
        $facade = new PrepareFacade($parser, false);

        // then
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid bound value for alternating key X. Expected string, but integer (4) given");

        // when
        $facade->getPattern();
    }

    public function invalidInputs_integers(): array
    {
        return [
            'bind @' => [new BindingParser('@a@b@c', ['a' => '', 'b' => '', 'c' => ['', 4]]),],
            'inject #' => [new InjectParser('@@@', ['', '', 'foo' => ['', 4]]),],
            'prepared []' => [new PreparedParser(['', '', [['', 4]]]),]
        ];
    }
}
