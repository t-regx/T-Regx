<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Utils\Agnostic\PcreDependant;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Figure\ExpectedFigures;
use TRegx\CleanRegex\Internal\Prepared\Figure\TokenFigures;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\PosixConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Posix;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\PosixOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Template\LiteralToken;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternAsEntities
 * @covers \TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser
 */
class PcreParserTest extends TestCase
{
    use PcreDependant;

    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param array $expectedBlocks
     */
    public function shouldParse(string $pattern, array $expectedBlocks)
    {
        // given
        $consumers = [
            new ControlConsumer(),
            new QuoteConsumer(),
            new EscapeConsumer(),
            new GroupConsumer(),
            new GroupCloseConsumer(),
            new PosixConsumer(),
            new CommentConsumer(),
            new LiteralConsumer(),
        ];

        // when
        $assertion = new PatternEntitiesAssertion($consumers);

        // then
        $assertion->assertPatternRepresents($pattern, $expectedBlocks);
    }

    public function patterns(): array
    {
        return \array_merge($this->generalPatterns(), $this->pcreDependantPatterns(), $this->pcreDependantFlags());
    }

    private function generalPatterns(): array
    {
        return [
            'empty'         => ['', []],
            'control'       => ['ab\c\word', ['ab', new Control('\\'), 'word']],
            'quotes'        => ['\Q{@}(hi)[hey]\E', [new Quote('{@}(hi)[hey]', true)]],
            'posix+quotes'  => ['[\Qa-z]\E$', [new PosixOpen(), new Quote('a-z]', true), new Posix('$')]],
            'groups+posix'  => ['(?x:[a-z])$', [new GroupOpenFlags('x'), new PosixOpen(), new Posix('a-z'), new PosixClose(), new GroupClose(), '$']],
            'backreference' => ['((?-2))', [new GroupOpen(), new GroupOpen(), '?-2', new GroupClose(), new GroupClose()]],

            'atomic #1' => ['(?>\d)', [new GroupOpen(), '?>', new Escaped('d'), new GroupClose()]],
            'atomic #2' => ['(*atomic:\d)', [new GroupOpen(), '*atomic:', new Escaped('d'), new GroupClose()]],

            'reset'            => ['(?^)', [new GroupRemainder('^')]],
            'reset,set'        => ['(?^ix)', [new GroupRemainder('^ix')]],
            'reset,set:'       => ['(?^ix:)', [new GroupOpenFlags('^ix'), new GroupClose()]],
            'reset,invalid #1' => ['(?-i^)', [new GroupOpen(), '?-i^', new GroupClose()]],
            'reset,invalid #2' => ['(?-^i)', [new GroupOpen(), '?-^i', new GroupClose()]],
            'reset,invalid #3' => ['(?i^)', [new GroupOpen(), '?i^', new GroupClose()]],
            'reset,unset'      => ['(?^-i)', [new GroupRemainder('^-i')]],
            'reset,unset:'     => ['(?^-i:)', [new GroupOpenFlags('^-i'), new GroupClose()]],
        ];
    }

    /**
     * @test
     */
    public function shouldParseWithFlags()
    {
        // given
        $consumers = [
            new GroupConsumer(),
            new GroupCloseConsumer(),
            new FiguresPlaceholderConsumer(new ExpectedFigures(new TokenFigures([
                new LiteralToken('one'),
                new LiteralToken('two'),
                new LiteralToken('three')
            ])))
        ];

        // when
        $assertion = new PatternEntitiesAssertion($consumers);

        // then
        $assertion->assertPatternRepresents('(?i:(?x:@(?m-x)@)@)', [
            new GroupOpenFlags('i'),
            new GroupOpenFlags('x'),
            new Placeholder(new LiteralToken('one')),
            new GroupRemainder('m-x'),
            new Placeholder(new LiteralToken('two')),
            new GroupClose(),
            new Placeholder(new LiteralToken('three')),
            new GroupClose(),
        ], '(?i:(?x:one(?m-x)two)three)');
    }

    private function pcreDependantPatterns(): array
    {
        return $this->pcreDependentStructure([
            'pcre1 #1' => ['(?x-)', [new GroupRemainder('x-')]],
            'pcre1 #2' => ['(?)', [new GroupRemainder('')]], // legal in PCRE
            'pcre1 #3' => ['(?nx)', [new GroupOpen(), '?nx', new GroupClose()]], // illegal in PCRE
            'pcre1 #4' => ['(?x--)', [new GroupRemainder('x--')]], // legal in PCRE
            'pcre1 #5' => ['(?-x-)', [new GroupRemainder('-x-')]], // legal in PCRE
            'pcre1 #6' => ['(?Xx)', [new GroupRemainder('Xx')]], // legal in PCRE
            'pcre1 #7' => ['(?Xx:)', [new GroupOpenFlags('Xx'), new GroupClose()]], // legal in PCRE
            'pcre1 #8' => ['(?Xnx)', [new GroupOpen(), '?Xnx', new GroupClose()]] // illegal in both
        ], [
            'pcre2 #1' => ['(?x-)', [new GroupRemainder('x-')]],
            'pcre2 #2' => ['(?)', [new GroupRemainder('')]], // illegal in PCRE2
            'pcre2 #3' => ['(?nx)', [new GroupRemainder('nx')]], // legal in PCRE2
            'pcre2 #4' => ['(?x--)', [new GroupOpen(), '?x--', new GroupClose()]], // illegal in PCRE2
            'pcre2 #5' => ['(?-x-)', [new GroupOpen(), '?-x-', new GroupClose()]], // illegal in PCRE2
            'pcre2 #6' => ['(?Xx)', [new GroupOpen(), '?Xx', new GroupClose()]], // illegal in PCRE2
            'pcre2 #7' => ['(?Xx:)', [new GroupOpen(), '?Xx:', new GroupClose()]], // illegal in PCRE2
            'pcre2 #8' => ['(?Xnx)', [new GroupOpen(), '?Xnx', new GroupClose()]] // illegal in both
        ]);
    }

    private function pcreDependantFlags(): array
    {
        return $this->pcreDependant([
            'pcre1 groups #1' => ['(?n)', [new GroupOpen(), '?n', new GroupClose()]], // illegal in PCRE
            'pcre1 groups #2' => ['foo(?UxmsiJX:)', ['foo', new GroupOpenFlags('UxmsiJX'), new GroupClose()]],
            'pcre1 groups #3' => ['foo(?Uxm-siJX:)', ['foo', new GroupOpenFlags('Uxm-siJX'), new GroupClose()]],
            'pcre1 groups #4' => ['foo(?UxmsiJX)', ['foo', new GroupRemainder('UxmsiJX')]],
            'pcre1 groups #5' => ['foo(?Uxms-iJX)', ['foo', new GroupRemainder('Uxms-iJX')]],
            'pcre1 groups #6' => ['foo(?i:)(?s:)(?m:)(?x:)(?X:)(?U:)(?J:)', [
                'foo',
                new GroupOpenFlags('i'), new GroupClose(),
                new GroupOpenFlags('s'), new GroupClose(),
                new GroupOpenFlags('m'), new GroupClose(),
                new GroupOpenFlags('x'), new GroupClose(),
                new GroupOpenFlags('X'), new GroupClose(),
                new GroupOpenFlags('U'), new GroupClose(),
                new GroupOpenFlags('J'), new GroupClose()
            ]]
        ], [
            'pcre2 groups #1' => ['(?X)', [new GroupOpen(), '?X', new GroupClose()]], // illegal in PCRE2
            'pcre2 groups #2' => ['foo(?UxmsiJn:)', ['foo', new GroupOpenFlags('UxmsiJn'), new GroupClose()]],
            'pcre2 groups #3' => ['foo(?Uxm-siJn:)', ['foo', new GroupOpenFlags('Uxm-siJn'), new GroupClose()]],
            'pcre2 groups #4' => ['foo(?UxmsiJn)', ['foo', new GroupRemainder('UxmsiJn')]],
            'pcre2 groups #5' => ['foo(?Uxms-iJn)', ['foo', new GroupRemainder('Uxms-iJn')]],
            'pcre2 groups #6' => ['foo(?i:)(?s:)(?m:)(?x:)(?n:)(?U:)(?J:)', [
                'foo',
                new GroupOpenFlags('i'), new GroupClose(),
                new GroupOpenFlags('s'), new GroupClose(),
                new GroupOpenFlags('m'), new GroupClose(),
                new GroupOpenFlags('x'), new GroupClose(),
                new GroupOpenFlags('n'), new GroupClose(),
                new GroupOpenFlags('U'), new GroupClose(),
                new GroupOpenFlags('J'), new GroupClose()
            ]]
        ]);
    }

    /**
     * @test
     * @dataProvider comments
     */
    public function shouldParseComments(string $flags, string $pattern, array $expectedBlocks)
    {
        // given
        // when
        $assertion = new PatternEntitiesAssertion([
            new EscapeConsumer(),
            new GroupConsumer(),
            new GroupCloseConsumer(),
            new CommentConsumer(),
            new LiteralConsumer(),
        ]);

        // when
        $assertion->assertPatternFlagsRepresent($pattern, $flags, $expectedBlocks);
    }

    public function comments(): array
    {
        return [
            'plain #1' => ['', 'car#hello', ['car#hello']],
            'plain #2' => ['', 'car\#hello', ['car', new Escaped('#'), 'hello']],
            'plain #3' => ['', "car\nhello", ["car\nhello"]],
            'plain #4' => ['', "car\\\nhello", ['car', new Escaped("\n"), 'hello']],

            'extended #1' => ['x', 'car#hello', ['car', new Comment('hello')]],
            'extended #2' => ['x', 'car\#hello', ['car', new Escaped('#'), 'hello']],
            'extended #3' => ['x', "car\nhello", ["car\nhello"]],
            'extended #4' => ['x', "car\\\nhello", ['car', new Escaped("\n"), 'hello']],

            'in-pattern #0' => ['x', '#', [new Comment('')]],
            'in-pattern #1' => ['', '(?x:car#hello)', [new GroupOpenFlags('x'), 'car', new Comment('hello)')]],
            'in-pattern #2' => ['x', '(?-x:car#hello)', [new GroupOpenFlags('-x'), 'car#hello', new GroupClose()]],
            'in-pattern #3' => ['x', "(?-x:(?x:#hello\n))", [
                new GroupOpenFlags('-x'),
                new GroupOpenFlags('x'),
                new Comment("hello\n"),
                new GroupClose(),
                new GroupClose()]
            ],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForUnmatchedLiteral()
    {
        // given
        $parser = new PcreParser(new Feed('\c'), new Flags(''), []);

        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        $parser->entities();
    }
}
