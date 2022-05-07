<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsPattern;
use TRegx\CleanRegex\Internal\Prepared\Figure\PlaceholderFigureException;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PcrePattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldTest_beFalse_forNotMatching()
    {
        // when
        $test = pattern('\d')->test('abc');

        // then
        $this->assertFalse($test);
    }

    /**
     * @test
     */
    public function shouldFails_beTrue_forNotMatched()
    {
        // when
        $fails = pattern('\d')->fails('abc');

        // then
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // when
        $count = pattern('\d+')->count('111-222-333');

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCount_0_notMatched()
    {
        // when
        $count = pattern('[a-z]+')->count('111-222-333');

        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldFilterArray()
    {
        // given
        $array = [
            'Uppercase',
            'lowercase',
            'Uppercase again',
            'lowercase again',
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filter();

        // then
        $this->assertSame(['Uppercase', 'Uppercase again'], $result);
    }

    /**
     * @test
     */
    public function shouldFilterArray_assoc()
    {
        // given
        $array = [
            'a' => 'Uppercase',
            'b' => 'lowercase',
            'c' => 'Uppercase again',
            'd' => 'lowercase again',
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filterAssoc();

        // then
        $expected = ['a' => 'Uppercase', 'c' => 'Uppercase again'];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldFilterArray_byKeys()
    {
        // given
        $array = [
            'Uppercase'       => 0,
            'lowercase'       => 1,
            'Uppercase again' => 2,
            'lowercase again' => 3,
        ];

        // when
        $result = pattern('[A-Z][a-z]+')->forArray($array)->filterByKeys();

        // then
        $expected = ['Uppercase' => 0, 'Uppercase again' => 2];
        $this->assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function shouldThrowPrettyErrorMessage(): void
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Two named subpatterns have the same name at offset 21');

        // when
        pattern('First(?<one>)?(?<one>)?')->test('Test');
    }

    /**
     * @test
     */
    public function shouldReturn_prune()
    {
        // when
        $result = pattern('\d+[.,]\d+')->prune('Foo for "14,43" and Bar for "2.32"');

        // then
        $this->assertSame('Foo for "" and Bar for ""', $result);
    }

    /**
     * @test
     */
    public function shouldThrow_prune_onMalformedPattern()
    {
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 5');

        // when
        pattern('Foo **')->prune('Foo bar');
    }

    /**
     * @test
     */
    public function shouldGet_split()
    {
        // when
        $matches = pattern(',')->split('Foo,Bar,Cat');

        // then
        $this->assertSame(['Foo', 'Bar', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_split_WithCapturingGroup()
    {
        // when
        $matches = pattern('(,)')->split('Foo,Bar,Cat');

        // then
        $this->assertSame(['Foo', ',', 'Bar', ',', 'Cat'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_literal()
    {
        // when
        $pattern = Pattern::literal('Foo{2}');

        // then
        $this->assertSamePattern('/Foo\{2\}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldGet_literal_WithFlags()
    {
        // when
        $pattern = Pattern::literal('Foo {2}', 'D');

        // then
        $this->assertSamePattern('/Foo\ \{2\}/D', $pattern);
    }

    /**
     * @test
     */
    public function shouldDelimiter_literal()
    {
        // when
        $pattern = Pattern::literal('Foo/{2}', 'm');

        // then
        $this->assertSamePattern('/Foo\/\{2\}/m', $pattern);
    }

    /**
     * @test
     */
    public function shouldCast_of()
    {
        // given
        $pattern = Pattern::of('Foo{1,2}/', 'n');

        // when
        $string = (string)$pattern;

        // then
        $this->assertSame('#Foo{1,2}/#n', $string);
    }

    /**
     * @test
     */
    public function shouldCast_pcre()
    {
        // given
        $pattern = PcrePattern::of('/Foo{1,2}/n');
        // when
        $string = (string)$pattern;
        // then
        $this->assertSame('/Foo{1,2}/n', $string);
    }

    /**
     * @test
     */
    public function shouldMatchExtendedCharacters()
    {
        // given
        $whitespace = " \t\n\x0B\f\r";

        // when
        $match = Pattern::inject('^@$', [$whitespace], 'x')->match($whitespace)->first();

        // then
        $this->assertSame($whitespace, $match);
    }

    /**
     * @test
     */
    public function shouldThrowForMismatchedNumberOfPlaceholders()
    {
        // then
        $this->expectException(PlaceholderFigureException::class);
        $this->expectExceptionMessage("Found a superfluous figure: string ('bar'). Used 1 placeholders, but 4 figures supplied.");

        // when
        Pattern::inject('You/her, (are|is) @ (you|her)', ['foo', 'bar', 'cat', 'door']);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashControl()
    {
        // when
        $pattern = Pattern::of('\c\\');

        // then
        $this->assertConsumesFirst(\chr(28), $pattern);
        $this->assertSamePattern('/\c\\{1}/', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptTrailingBackslashControlCompose()
    {
        // when
        $pattern = Pattern::compose(['\c\\']);

        // then
        $this->assertTrue($pattern->testAll(\chr(28)));
    }

    /**
     * @test
     */
    public function shouldPatternNewlineIsLineFeed()
    {
        // when
        $lines = Pattern::of('^.*$', 'm')->match("\rone\r\ntwo\n\nthree\rfour\v\f")->all();

        // then
        $this->assertSame(["\rone\r", 'two', '', "three\rfour\v\f"], $lines);
    }

    /**
     * @test
     */
    public function shouldThrowForFlagResetInPattern()
    {
        // given
        $pattern = Pattern::of('foo', '^x');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage("Unknown modifier '^'");
        // when
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function shouldNotMistakeEmptyStringForZero()
    {
        // when
        $matches = Pattern::of('|0')->match('0')->distinct();
        // then
        $this->assertSame(['', '0'], $matches);
    }
}
