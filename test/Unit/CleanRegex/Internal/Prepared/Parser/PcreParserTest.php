<?php
namespace Test\Unit\CleanRegex\Internal\Prepared\Parser;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\ConstantConvention;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ConstantPlaceholderConsumer;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\ThrowConvention;
use Test\Fakes\CleanRegex\Internal\Prepared\Template\Cluster\FakeCluster;
use Test\Utils\Agnostic\PcreDependant;
use Test\Utils\Prepared\PatternEntitiesAssertion;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ArrayClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CharacterClassConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\CommentConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\ControlConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\EscapeConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupCloseConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\GroupConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\LiteralConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\QuoteConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Character;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\Feed\Feed;
use TRegx\CleanRegex\Internal\Prepared\Parser\PcreParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternPhrase
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
     * @param string|null $expected
     */
    public function shouldParse(string $pattern, array $expectedBlocks, string $expected = null)
    {
        // given
        $consumers = [
            new ControlConsumer(),
            new QuoteConsumer(),
            new EscapeConsumer(),
            new GroupConsumer(PcreAutoCapture::autoCapture()),
            new GroupCloseConsumer(),
            new CharacterClassConsumer(),
            new CommentConsumer(new ThrowConvention()),
            new LiteralConsumer(),
        ];

        // when
        $assertion = new PatternEntitiesAssertion($consumers);

        // then
        $assertion->assertPatternRepresents($pattern, $expectedBlocks, $expected ?? $pattern);
    }

    public function patterns(): array
    {
        return \array_merge($this->generalPatterns(), $this->pcreDependantPatterns(), $this->pcreDependantFlags());
    }

    private function generalPatterns(): array
    {
        return [
            'empty'        => ['', []],
            'control'      => ['ab\c\word', ['ab', new Control('\\'), 'word']],
            'quotes'       => ['\Q{@}(hi)[hey]\E', [new Quote('{@}(hi)[hey]', true)]],
            'class+quotes' => ['[\Qa-z]\E$', [new ClassOpen(), new Quote('a-z]', true), new Character('$')]],
            'groups+class' => ['(?x:[a-z])$', [new GroupOpenFlags('x', new IdentityOptionSetting('x')), new ClassOpen(), new Character('a-z'), new ClassClose(), new GroupClose(), '$']],

            'reset'        => ['(?^)', [new GroupRemainder('^', new IdentityOptionSetting('^'))]],
            'reset,set'    => ['(?^ix)', [new GroupRemainder('^ix', new IdentityOptionSetting('^ix'))]],
            'reset,set:'   => ['(?^ix:)', [new GroupOpenFlags('^ix', new IdentityOptionSetting('^ix')), new GroupClose()]],
            'reset,unset'  => ['(?^-i)', [new GroupRemainder('^-i', new IdentityOptionSetting('^-i'))]],
            'reset,unset:' => ['(?^-i:)', [new GroupOpenFlags('^-i', new IdentityOptionSetting('^-i')), new GroupClose()]],
        ];
    }

    private function pcreDependantPatterns(): array
    {
        return $this->pcreDependentStructure([
            'pcre1 #1' => ['(?x-)', [new GroupRemainder('x-', new IdentityOptionSetting('x-'))]], // legal in both
            'pcre1 #2' => ['(?)', [new GroupRemainder('', new IdentityOptionSetting(''))]], // legal in PCRE
            'pcre1 #3' => ['(?nx)', [new GroupRemainder('nx', new IdentityOptionSetting('nx'))], '(?x)'], // illegal in PCRE
            'pcre1 #4' => ['(?x--)', [new GroupRemainder('x--', new IdentityOptionSetting('x--'))]], // legal in PCRE
            'pcre1 #5' => ['(?-x-)', [new GroupRemainder('-x-', new IdentityOptionSetting('-x-'))]], // legal in PCRE
            'pcre1 #6' => ['(?Xx)', [new GroupRemainder('Xx', new IdentityOptionSetting('Xx'))]], // legal in PCRE
            'pcre1 #7' => ['(?Xx:)', [new GroupOpenFlags('Xx', new IdentityOptionSetting('Xx')), new GroupClose()]], // legal in PCRE
            'pcre1 #8' => ['(?Xnx)', [new GroupRemainder('Xnx', new IdentityOptionSetting('Xnx'))], '(?Xx)'], // illegal in both

            'pcre1 backreference' => ['((?-2))', [new GroupOpen(''), new GroupOpen('?'), '-2', new GroupClose(), new GroupClose()]],

            'pcre1 atomic #1' => ['(?>\d)', [new GroupOpen('?>'), new Escaped('d'), new GroupClose()]],
            'pcre1 atomic #2' => ['(*atomic:\d)', [new GroupOpen('*'), 'atomic:', new Escaped('d'), new GroupClose()]],

            'pcre1 reset,invalid #1' => ['(?-i^)', [new GroupOpen('?'), '-i^', new GroupClose()]],
            'pcre1 reset,invalid #2' => ['(?-^i)', [new GroupOpen('?'), '-^i', new GroupClose()]],
            'pcre1 reset,invalid #3' => ['(?i^)', [new GroupOpen('?'), 'i^', new GroupClose()]],
        ], [
            'pcre2 #1' => ['(?x-)', [new GroupRemainder('x-', new IdentityOptionSetting('x-'))]], // legal in both
            'pcre2 #2' => ['(?)', [new GroupRemainder('', new IdentityOptionSetting(''))]], // illegal in PCRE2
            'pcre2 #3' => ['(?nx)', [new GroupRemainder('nx', new IdentityOptionSetting('nx'))]], // legal in PCRE2
            'pcre2 #4' => ['(?x--)', [new GroupOpen(''), '?x--', new GroupClose()]], // illegal in PCRE2
            'pcre2 #5' => ['(?-x-)', [new GroupOpen(''), '?-x-', new GroupClose()]], // illegal in PCRE2
            'pcre2 #6' => ['(?Xx)', [new GroupOpen(''), '?Xx', new GroupClose()]], // illegal in PCRE2
            'pcre2 #7' => ['(?Xx:)', [new GroupOpen(''), '?Xx:', new GroupClose()]], // illegal in PCRE2
            'pcre2 #8' => ['(?Xnx)', [new GroupOpen(''), '?Xnx', new GroupClose()]], // illegal in both

            'pcre2 backreference' => ['((?-2))', [new GroupOpen(''), new GroupOpen(''), '?-2', new GroupClose(), new GroupClose()]],

            'pcre2 atomic #1' => ['(?>\d)', [new GroupOpen(''), '?>', new Escaped('d'), new GroupClose()]],
            'pcre2 atomic #2' => ['(*atomic:\d)', [new GroupOpen(''), '*atomic:', new Escaped('d'), new GroupClose()]],

            'pcre2 reset,invalid #1' => ['(?-i^)', [new GroupOpen(''), '?-i^', new GroupClose()]],
            'pcre2 reset,invalid #2' => ['(?-^i)', [new GroupOpen(''), '?-^i', new GroupClose()]],
            'pcre2 reset,invalid #3' => ['(?i^)', [new GroupOpen(''), '?i^', new GroupClose()]],
        ]);
    }

    private function pcreDependantFlags(): array
    {
        return $this->pcreDependant([
            'pcre1 groups #3' => ['foo(?UxmsiJX:)', ['foo', new GroupOpenFlags('UxmsiJX', new IdentityOptionSetting('UxmsiJX')), new GroupClose()]],
            'pcre1 groups #4' => ['foo(?Uxm-siJX:)', ['foo', new GroupOpenFlags('Uxm-siJX', new IdentityOptionSetting('Uxm-siJX')), new GroupClose()]],
            'pcre1 groups #5' => ['foo(?UxmsiJX)', ['foo', new GroupRemainder('UxmsiJX', new IdentityOptionSetting('UxmsiJX'))]],
            'pcre1 groups #6' => ['foo(?Uxms-iJX)', ['foo', new GroupRemainder('Uxms-iJX', new IdentityOptionSetting('Uxms-iJX'))]],
            'pcre1 groups #7' => ['foo(?i:)(?s:)(?m:)(?x:)(?X:)(?U:)(?J:)', [
                'foo',
                new GroupOpenFlags('i', new IdentityOptionSetting('i')), new GroupClose(),
                new GroupOpenFlags('s', new IdentityOptionSetting('s')), new GroupClose(),
                new GroupOpenFlags('m', new IdentityOptionSetting('m')), new GroupClose(),
                new GroupOpenFlags('x', new IdentityOptionSetting('x')), new GroupClose(),
                new GroupOpenFlags('X', new IdentityOptionSetting('X')), new GroupClose(),
                new GroupOpenFlags('U', new IdentityOptionSetting('U')), new GroupClose(),
                new GroupOpenFlags('J', new IdentityOptionSetting('J')), new GroupClose()
            ]]
        ], [
            'pcre2 groups #1' => ['(?X)', [new GroupOpen(''), '?X', new GroupClose()]], // illegal in PCRE2
            'pcre2 groups #2' => ['(?n)', [new GroupRemainder('n', new IdentityOptionSetting('n'))]],
            'pcre2 groups #3' => ['foo(?UxmsiJn:)', ['foo', new GroupOpenFlags('UxmsiJn', new IdentityOptionSetting('UxmsiJn')), new GroupClose()]],
            'pcre2 groups #4' => ['foo(?Uxm-siJn:)', ['foo', new GroupOpenFlags('Uxm-siJn', new IdentityOptionSetting('Uxm-siJn')), new GroupClose()]],
            'pcre2 groups #5' => ['foo(?UxmsiJn)', ['foo', new GroupRemainder('UxmsiJn', new IdentityOptionSetting('UxmsiJn'))]],
            'pcre2 groups #6' => ['foo(?Uxms-iJn)', ['foo', new GroupRemainder('Uxms-iJn', new IdentityOptionSetting('Uxms-iJn'))]],
            'pcre2 groups #7' => ['foo(?i:)(?s:)(?m:)(?x:)(?n:)(?U:)(?J:)', [
                'foo',
                new GroupOpenFlags('i', new IdentityOptionSetting('i')), new GroupClose(),
                new GroupOpenFlags('s', new IdentityOptionSetting('s')), new GroupClose(),
                new GroupOpenFlags('m', new IdentityOptionSetting('m')), new GroupClose(),
                new GroupOpenFlags('x', new IdentityOptionSetting('x')), new GroupClose(),
                new GroupOpenFlags('n', new IdentityOptionSetting('n')), new GroupClose(),
                new GroupOpenFlags('U', new IdentityOptionSetting('U')), new GroupClose(),
                new GroupOpenFlags('J', new IdentityOptionSetting('J')), new GroupClose()
            ]]
        ]);
    }

    /**
     * @test
     */
    public function shouldParseWithFlags()
    {
        // given
        $clusters = new ExpectedClusters(new ArrayClusters([
            new FakeCluster('one'),
            new FakeCluster('two'),
            new FakeCluster('three')
        ]));
        $consumers = [
            new GroupConsumer(PcreAutoCapture::autoCapture()),
            new GroupCloseConsumer(),
            new FiguresPlaceholderConsumer($clusters)
        ];

        // when
        $assertion = new PatternEntitiesAssertion($consumers);

        // then
        $assertion->assertPatternRepresents('(?i:(?x:@(?m-x)@)@)', [
            new GroupOpenFlags('i', new IdentityOptionSetting('i')),
            new GroupOpenFlags('x', new IdentityOptionSetting('x')),
            new Placeholder($clusters, SubpatternFlags::from(Flags::from('x'))),
            new GroupRemainder('m-x', new IdentityOptionSetting('m-x')),
            new Placeholder($clusters, SubpatternFlags::empty()),
            new GroupClose(),
            new Placeholder($clusters, SubpatternFlags::empty()),
            new GroupClose(),
        ], '(?i:(?x:one(?m-x)two)three)');
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
            new GroupConsumer(PcreAutoCapture::autoCapture()),
            new GroupCloseConsumer(),
            new CommentConsumer(new ConstantConvention("\n")),
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
            'in-pattern #1' => ['', '(?x:car#hello)', [new GroupOpenFlags('x', new IdentityOptionSetting('x')), 'car', new Comment('hello)')]],
            'in-pattern #2' => ['x', '(?-x:car#hello)', [new GroupOpenFlags('-x', new IdentityOptionSetting('-x')), 'car#hello', new GroupClose()]],
            'in-pattern #3' => ['x', "(?-x:(?x:#hello\n))", [
                new GroupOpenFlags('-x', new IdentityOptionSetting('-x')),
                new GroupOpenFlags('x', new IdentityOptionSetting('x')),
                new Comment("hello\n"),
                new GroupClose(),
                new GroupClose()]
            ],
        ];
    }

    /**
     * @test
     */
    public function shouldThrowForInapplicableConsumer()
    {
        // given
        $parser = new PcreParser(new Feed('\c'), SubpatternFlags::empty(), []);
        // then
        $this->expectException(InternalCleanRegexException::class);
        // when
        $parser->entities();
    }

    /**
     * @test
     */
    public function shouldParsePlaceholderInGroupName()
    {
        // given
        $consumers = [
            new GroupConsumer(PcreAutoCapture::autoCapture()),
            new GroupCloseConsumer(),
            new ConstantPlaceholderConsumer('v'),
            new LiteralConsumer()
        ];
        // when
        $assertion = new PatternEntitiesAssertion($consumers);
        // then
        $assertion->assertPatternRepresents('(?<@>)', [new GroupOpen('?<@>'), new GroupClose()], '(?<@>)');
    }
}
