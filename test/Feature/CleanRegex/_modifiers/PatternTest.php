<?php
namespace Test\Feature\CleanRegex\_modifiers;

use PHPUnit\Framework\TestCase;
use Test\Utils\Agnostic\PcreDependant;
use Test\Utils\Assertion\AssertsDetail;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\Structure\AssertsStructure;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    use AssertsPattern, AssertsDetail, TestCasePasses, AssertsStructure, PcreDependant;

    /**
     * @test
     */
    public function caseInsensitive(): void
    {
        // given
        $pattern = Pattern::of('foo', Pattern::CASE_INSENSITIVE);
        // when, then
        $this->assertConsumesFirst('FOO', $pattern);
    }

    /**
     * @test
     */
    public function unicode(): void
    {
        // given
        $pattern = Pattern::of('€+', Pattern::UNICODE);
        // when, then
        $this->assertConsumesFirst('€€€', $pattern);
    }

    /**
     * @test
     */
    public function multiline(): void
    {
        // given
        $pattern = Pattern::of('(^one$\n?)+', Pattern::MULTILINE);
        // when, then
        $this->assertConsumesFirst("one\none", $pattern);
    }

    /**
     * @test
     */
    public function singleline(): void
    {
        // given
        $pattern = Pattern::of('one.two', Pattern::SINGLELINE);
        // when, then
        $this->assertConsumesFirst("one\ntwo", $pattern);
    }

    /**
     * @test
     */
    public function extendedMode(): void
    {
        // given
        $pattern = Pattern::of(" f o o: #comment\n bar", Pattern::IGNORE_WHITESPACE);
        // when, then
        $this->assertConsumesFirst("foo:bar", $pattern);
    }

    /**
     * @test
     */
    public function anchored(): void
    {
        // given
        $pattern = Pattern::of('foo', Pattern::ANCHORED);
        // when, then
        $this->assertConsumesAll('foofoo foo', ['foo', 'foo'], $pattern);
        $this->assertPatternFails($pattern, ' foo');
    }

    /**
     * @test
     * @dataProvider greedyAndUngreedyPatterns
     */
    public function greedynessInverted(Pattern $pattern, bool $expectedGreedy): void
    {
        // when, then
        $this->assertConsumesFirstGroup('foooo', $expectedGreedy ? 'foooo' : 'fo', $pattern);
    }

    public function greedyAndUngreedyPatterns(): array
    {
        return [
            [Pattern::of('(fo+)'), true],
            [Pattern::of('(fo+?)'), false],

            [Pattern::of('(fo+)', Pattern::GREEDYNESS_INVERTED), false],
            [Pattern::of('(fo+?)', Pattern::GREEDYNESS_INVERTED), true],
        ];
    }

    /**
     * @test
     */
    public function restrictiveEscape()
    {
        // given
        $pattern = Pattern::of('\i', Pattern::RESTRICTIVE_ESCAPE);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Unrecognized character follows \ at offset 1');
        // when
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function duplicateNames()
    {
        // given
        $pattern = Pattern::of('(?<name>foo),(?<name>bar)', Pattern::DUPLICATE_NAMES);
        // when
        $this->assertConsumesFirst('foo,bar', $pattern);
    }

    /**
     * @test
     */
    public function dollarEndOnly()
    {
        // given
        $pattern = Pattern::of('^one$', Pattern::DOLLAR_ENDONLY);
        // when
        $this->assertPatternFails($pattern, "one\n");
    }

    /**
     * @test
     */
    public function study()
    {
        // given
        $pattern = Pattern::of('^one$', Pattern::STUDY);
        // when
        $this->assertPatternIs("/^one$/S", $pattern);
    }

    /**
     * @test
     */
    public function noAutoCapture(): void
    {
        // given
        $pattern = Pattern::of('(foo),(?<bar>bar)', Pattern::NO_AUTOCAPTURE);
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
    }
}
