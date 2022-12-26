<?php
namespace Test\Unit\CleanRegex\Internal\Prepared;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\NoAutoCapture\IdentityOptionSettingAutoCapture;
use Test\Fakes\CleanRegex\Internal\NoAutoCapture\ThrowAutoCapture;
use Test\Fakes\CleanRegex\Internal\Prepared\Parser\Consumer\ThrowPlaceholderConsumer;
use Test\Fakes\CleanRegex\Internal\Prepared\Template\Cluster\FakeCluster;
use Test\Utils\Agnostic\PcreDependant;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\IdentityOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\OptionSetting\LegacyOptionSetting;
use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Flags;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ArrayClusters;
use TRegx\CleanRegex\Internal\Prepared\Cluster\ExpectedClusters;
use TRegx\CleanRegex\Internal\Prepared\Parser\Consumer\FiguresPlaceholderConsumer;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Character;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\ClassOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Comment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Control;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Entity;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Escaped;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupClose;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupComment;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupNull;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpen;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupOpenFlags;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\GroupRemainder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Literal;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Placeholder;
use TRegx\CleanRegex\Internal\Prepared\Parser\Entity\Quote;
use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;
use TRegx\CleanRegex\Internal\Prepared\Pattern\SubpatternFlagsStringPattern;
use TRegx\CleanRegex\Internal\Prepared\PatternEntities;
use TRegx\CleanRegex\Internal\Prepared\Phrase\CompositePhrase;
use TRegx\Pcre;

/**
 * @covers \TRegx\CleanRegex\Internal\Prepared\PatternEntities
 */
class PatternEntitiesTest extends TestCase
{
    use PcreDependant;

    /**
     * @test
     */
    public function shouldParseLookAroundAssertion()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('\K', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new Escaped('K')
        ]);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRemainder()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('()(?)', SubpatternFlags::empty()), PcreAutoCapture::autoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $setting = Pcre::pcre2() ? new IdentityOptionSetting('') : new LegacyOptionSetting('');
        $this->assertEntitiesEqual($asEntities, [
            new GroupOpen(''),
            new GroupClose(),
            new GroupRemainder('', $setting)
        ]);
    }

    /**
     * @test
     */
    public function shouldConsumeImmediatelyClosedGroupsRepeatedly()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('())))(?)', SubpatternFlags::empty()),
            PcreAutoCapture::autoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new GroupOpen(''),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupClose(),
            new GroupRemainder('', Pcre::pcre2() ? new IdentityOptionSetting('') : new LegacyOptionSetting('')),
        ]);
    }

    /**
     * @test
     */
    public function shouldParseImmediatelyClosedCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[]]]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new ClassOpen(),
            new Character(']'),
            new ClassClose(),
            new Literal(']'),
        ]);
    }

    /**
     * @test
     */
    public function shouldParseDoubleColorWordInCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[:alpha:]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new ClassOpen(),
            new Character(':alpha:'),
            new ClassClose()
        ]);
    }

    /**
     * @test
     */
    public function shouldParseEscapedClosingCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[F\]O]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new ClassOpen(),
            new Character('F\]O'),
            new ClassClose()
        ]);
    }

    /**
     * @test
     */
    public function shouldParseNestedCharacterClass()
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern('[01[:alpha:]%]', SubpatternFlags::empty()), new ThrowAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new ClassOpen(),
            new Character('01'),
            new Character('[:alpha:]'),
            new Character('%'),
            new ClassClose()
        ]);
    }

    /**
     * @test
     * @dataProvider comments
     */
    public function shouldParseComments(string $flags, string $pattern, array $expectedBlocks)
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern($pattern, SubpatternFlags::from(Flags::from($flags))),
            new IdentityOptionSettingAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, $expectedBlocks);
    }

    public function comments(): array
    {
        return [
            'plain #1' => ['', 'car#hello', [new Literal('car#hello')]],
            'plain #2' => ['', 'car\#hello', [new Literal('car'), new Escaped('#'), new Literal('hello')]],
            'plain #3' => ['', "car\nhello", [new Literal("car\nhello")]],
            'plain #4' => ['', "car\\\nhello", [new Literal('car'), new Escaped("\n"), new Literal('hello')]],

            'extended #1' => ['x', 'car#hello', [new Literal('car'), new Comment('hello')]],
            'extended #2' => ['x', 'car\#hello', [new Literal('car'), new Escaped('#'), new Literal('hello')]],
            'extended #3' => ['x', "car\nhello", [new Literal("car\nhello")]],
            'extended #4' => ['x', "car\\\nhello", [new Literal('car'), new Escaped("\n"), new Literal('hello')]],

            'in-pattern #0' => ['x', '#', [
                new Comment('')
            ]],
            'in-pattern #1' => ['', '(?x:car#hello)', [
                new GroupOpenFlags('x', new IdentityOptionSetting('x')), new Literal('car'), new Comment('hello)')
            ]],
            'in-pattern #2' => ['x', '(?-x:car#hello)', [
                new GroupOpenFlags('-x', new IdentityOptionSetting('-x')), new Literal('car#hello'), new GroupClose()
            ]],
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
     * @dataProvider characterClasses
     */
    public function test(string $pattern, array $expected)
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern($pattern, SubpatternFlags::empty()),
            new IdentityOptionSettingAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, $expected);
    }

    public function characterClasses(): array
    {
        $quotes = named([
            ['\Q', [new Quote('', false)]],
            ['\E', [new Escaped('E')]],
            ['\Q\E', [new Quote('', true)]],
            ['\Qfoo\E', [new Quote('foo', true)]],
            ['\Qfoo', [new Quote('foo', false)]],
            ['\Q@\E', [new Quote('@', true)]],
            ['\Qx\\\E', [new Quote('x\\', true)]],
            ['\Qx\\\\\E', [new Quote('x\\\\', true)]],
            ['\Q\Q@\E', [new Quote('\Q@', true)]],
            ['\Q@\E\E', [new Quote('@', true), new Escaped('E')]],
            ['\Q{@}(hi)[hey]\E', [new Quote('{@}(hi)[hey]', true)]],
            ['\Q:foo(bar)\x', [new Quote(':foo(bar)\x', false)]],
            ["\Q:foo(\n)bar\E", [new Quote(":foo(\n)bar", true)]],
        ]);
        $characterClasses = named([
            ['[', [new ClassOpen()]],
            ['[foo\bar]', [new ClassOpen(), new Character('foo\bar'), new ClassClose()]],
            ['[foo\]bar]', [new ClassOpen(), new Character('foo\]bar'), new ClassClose()]],

            ['[\\', [new ClassOpen(), new Character('\\')]],
            ['[\]', [new ClassOpen(), new Character('\]')]],
            ['[\]]', [new ClassOpen(), new Character('\]'), new ClassClose()]],
            ['[[]]', [new ClassOpen(), new Character('['), new ClassClose(), new Literal(']')]],
            ['[[]\]', [new ClassOpen(), new Character('['), new ClassClose(), new Escaped(']')]],
            ['[\Q', [new ClassOpen(), new Quote('', false)]],

            ['[@]', [new ClassOpen(), new Character('@'), new ClassClose()]],
            ['[&]', [new ClassOpen(), new Character('&'), new ClassClose()]],

            ['[\Qa-z]\E+]', [new ClassOpen(), new Quote('a-z]', true), new Character('+'), new ClassClose()]],
            ['[\Qa-z\]\\\E+]', [new ClassOpen(), new Quote('a-z\]\\', true), new Character('+'), new ClassClose()]],
            ['[\Qb\Ec\Qd\Ee', [new ClassOpen(), new Quote('b', true), new Character('c'), new Quote('d', true), new Character('e')]],
            ['[(?x:a-z])$', [new ClassOpen(), new Character('(?x:a-z'), new ClassClose(), new GroupClose(), new Literal('$')]],
        ]);
        $controls = named([
            ['\cx', [new Control('x')]],
            ['\c', [new Control('')]],
        ]);
        $escapes = named([
            ['\n\foo', [new Escaped('n'), new Escaped('f'), new Literal('oo')]],
            ["\\\n", [new Escaped("\n")]],
            ['\\\\x', [new Escaped('\\'), new Literal('x')]],
            ['\@', [new Escaped('@')]],
            ['\&', [new Escaped('&')]],
        ]);
        $groups = named([
            ['(?m)', [new GroupRemainder('m', new IdentityOptionSetting('m'))]],
            ['(?i:bar)', [new GroupOpenFlags('i', new IdentityOptionSetting('i')), new Literal('bar'), new GroupClose()]],
            ['(?#c:\)', [new GroupComment('c:\\'), new GroupClose()]],
            ['(?#c:\)hello)', [new GroupComment('c:\\'), new GroupClose(), new Literal('hello'), new GroupClose()]],
            ['(', [new GroupOpen('')]],
            ['(?:)', [new GroupNull()]],
            ['(?:bar)', [new GroupOpenFlags('', new IdentityOptionSetting('')), new Literal('bar'), new GroupClose()]],
        ]);
        $groupNames = named([
            ['(?<@>)', [new GroupOpen('?<@>'), new GroupClose()], '(?<@>)'],
        ]);
        if (Pcre::pcre2()) {
            $pcreDependant = named([
                ['(?c:bar)', [new GroupOpen(''), new Literal('?c:bar'), new GroupClose()]],
                ['(?ismx-nUJ:', [new GroupOpenFlags('ismx-nUJ', new IdentityOptionSetting('ismx-nUJ'))], '(?ismx-nUJ:'],
            ]);
        } else {
            $pcreDependant = named([
                ['(?c:bar)', [new GroupOpen('?'), new Literal('c:bar'), new GroupClose()]],
                ['(?ismx--XUJ--:', [new GroupOpenFlags('ismx--XUJ--', new IdentityOptionSetting('ismx--XUJ--'))]],
            ]);
        }
        return $quotes + $characterClasses + $controls + $escapes + $groups + $groupNames + $pcreDependant;
    }

    /**
     * @test
     * @dataProvider terminatingEscapes
     */
    public function shouldThrowForTerminatingEscape(string $pattern)
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern($pattern, SubpatternFlags::empty()),
            new IdentityOptionSettingAutoCapture(), new ThrowPlaceholderConsumer());
        // then
        $this->expectException(TrailingBackslashException::class);
        // when
        $asEntities->phrases();
    }

    public function terminatingEscapes(): array
    {
        return provided(['\\', '\\\\\\']);
    }

    /**
     * @test
     * @dataProvider patterns
     * @param string $pattern
     * @param array $expected
     * @param string|null $expectedPattern
     */
    public function shouldParse(string $pattern, array $expected, string $expectedPattern = null)
    {
        // given
        $asEntities = new PatternEntities(new SubpatternFlagsStringPattern($pattern, SubpatternFlags::empty()),
            new IdentityOptionSettingAutoCapture(), new ThrowPlaceholderConsumer());
        // when, then
        $this->assertEntitiesEqual($asEntities, $expected);
        $this->assertEntitiesRepresent($asEntities, $expectedPattern ?? $pattern);
    }

    public function patterns(): array
    {
        return \array_merge($this->generalPatterns(), $this->pcreDependantPatterns(), $this->pcreDependantFlags());
    }

    /**
     * @test
     */
    public function shouldParseWithFlags()
    {
        // given
        $asEntities = new PatternEntities(
            new SubpatternFlagsStringPattern('(?i:(?x:@(?m-x)@)@)', SubpatternFlags::empty()),
            new IdentityOptionSettingAutoCapture(),
            new FiguresPlaceholderConsumer(new ExpectedClusters(new ArrayClusters([
                new FakeCluster('one'),
                new FakeCluster('two'),
                new FakeCluster('three')
            ])))
        );
        // when, then
        $this->assertEntitiesEqual($asEntities, [
            new GroupOpenFlags('i', new IdentityOptionSetting('i')),
            new GroupOpenFlags('x', new IdentityOptionSetting('x')),
            new Placeholder(new FakeCluster('one'), SubpatternFlags::from(Flags::from('x'))),
            new GroupRemainder('m-x', new IdentityOptionSetting('m-x')),
            new Placeholder(new FakeCluster('two'), SubpatternFlags::empty()),
            new GroupClose(),
            new Placeholder(new FakeCluster('three'), SubpatternFlags::empty()),
            new GroupClose(),
        ]);
        $this->assertEntitiesRepresent($asEntities, '(?i:(?x:one(?m-x)two)three)');
    }

    private function generalPatterns(): array
    {
        return [
            'empty'        => ['', []],
            'control'      => ['ab\c\word', [new Literal('ab'), new Control('\\'), new Literal('word')]],
            'quotes'       => ['\Q{@}(hi)[hey]\E', [new Quote('{@}(hi)[hey]', true)]],
            'class+quotes' => ['[\Qa-z]\E$', [new ClassOpen(), new Quote('a-z]', true), new Character('$')]],
            'groups+class' => ['(?x:[a-z])$', [new GroupOpenFlags('x', new IdentityOptionSetting('x')), new ClassOpen(), new Character('a-z'), new ClassClose(), new GroupClose(), new Literal('$')]],

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
            'pcre1 #3' => ['(?nx)', [new GroupRemainder('nx', new IdentityOptionSetting('nx'))], '(?nx)'], // illegal in PCRE
            'pcre1 #4' => ['(?x--)', [new GroupRemainder('x--', new IdentityOptionSetting('x--'))]], // legal in PCRE
            'pcre1 #5' => ['(?-x-)', [new GroupRemainder('-x-', new IdentityOptionSetting('-x-'))]], // legal in PCRE
            'pcre1 #6' => ['(?Xx)', [new GroupRemainder('Xx', new IdentityOptionSetting('Xx'))]], // legal in PCRE
            'pcre1 #7' => ['(?Xx:)', [new GroupOpenFlags('Xx', new IdentityOptionSetting('Xx')), new GroupClose()]], // legal in PCRE
            'pcre1 #8' => ['(?Xnx)', [new GroupRemainder('Xnx', new IdentityOptionSetting('Xnx'))], '(?Xnx)'], // illegal in both

            'pcre1 backreference' => ['((?-2))', [new GroupOpen(''), new GroupOpen('?'), new Literal('-2'), new GroupClose(), new GroupClose()]],

            'pcre1 atomic #1' => ['(?>\d)', [new GroupOpen('?>'), new Escaped('d'), new GroupClose()]],
            'pcre1 atomic #2' => ['(*atomic:\d)', [new GroupOpen('*'), new Literal('atomic:'), new Escaped('d'), new GroupClose()]],

            'pcre1 reset,invalid #1' => ['(?-i^)', [new GroupOpen('?'), new Literal('-i^'), new GroupClose()]],
            'pcre1 reset,invalid #2' => ['(?-^i)', [new GroupOpen('?'), new Literal('-^i'), new GroupClose()]],
            'pcre1 reset,invalid #3' => ['(?i^)', [new GroupOpen('?'), new Literal('i^'), new GroupClose()]],
        ], [
            'pcre2 #1' => ['(?x-)', [new GroupRemainder('x-', new IdentityOptionSetting('x-'))]], // legal in both
            'pcre2 #2' => ['(?)', [new GroupRemainder('', new IdentityOptionSetting(''))]], // illegal in PCRE2
            'pcre2 #3' => ['(?nx)', [new GroupRemainder('nx', new IdentityOptionSetting('nx'))]], // legal in PCRE2
            'pcre2 #4' => ['(?x--)', [new GroupOpen(''), new Literal('?x--'), new GroupClose()]], // illegal in PCRE2
            'pcre2 #5' => ['(?-x-)', [new GroupOpen(''), new Literal('?-x-'), new GroupClose()]], // illegal in PCRE2
            'pcre2 #6' => ['(?Xx)', [new GroupOpen(''), new Literal('?Xx'), new GroupClose()]], // illegal in PCRE2
            'pcre2 #7' => ['(?Xx:)', [new GroupOpen(''), new Literal('?Xx:'), new GroupClose()]], // illegal in PCRE2
            'pcre2 #8' => ['(?Xnx)', [new GroupOpen(''), new Literal('?Xnx'), new GroupClose()]], // illegal in both

            'pcre2 backreference' => ['((?-2))', [new GroupOpen(''), new GroupOpen(''), new Literal('?-2'), new GroupClose(), new GroupClose()]],

            'pcre2 atomic #1' => ['(?>\d)', [new GroupOpen(''), new Literal('?>'), new Escaped('d'), new GroupClose()]],
            'pcre2 atomic #2' => ['(*atomic:\d)', [new GroupOpen(''), new Literal('*atomic:'), new Escaped('d'), new GroupClose()]],

            'pcre2 reset,invalid #1' => ['(?-i^)', [new GroupOpen(''), new Literal('?-i^'), new GroupClose()]],
            'pcre2 reset,invalid #2' => ['(?-^i)', [new GroupOpen(''), new Literal('?-^i'), new GroupClose()]],
            'pcre2 reset,invalid #3' => ['(?i^)', [new GroupOpen(''), new Literal('?i^'), new GroupClose()]],
        ]);
    }

    private function pcreDependantFlags(): array
    {
        return $this->pcreDependant([
            'pcre1 groups #3' => ['foo(?UxmsiJX:)', [new Literal('foo'), new GroupOpenFlags('UxmsiJX', new IdentityOptionSetting('UxmsiJX')), new GroupClose()]],
            'pcre1 groups #4' => ['foo(?Uxm-siJX:)', [new Literal('foo'), new GroupOpenFlags('Uxm-siJX', new IdentityOptionSetting('Uxm-siJX')), new GroupClose()]],
            'pcre1 groups #5' => ['foo(?UxmsiJX)', [new Literal('foo'), new GroupRemainder('UxmsiJX', new IdentityOptionSetting('UxmsiJX'))]],
            'pcre1 groups #6' => ['foo(?Uxms-iJX)', [new Literal('foo'), new GroupRemainder('Uxms-iJX', new IdentityOptionSetting('Uxms-iJX'))]],
            'pcre1 groups #7' => ['foo(?i:)(?s:)(?m:)(?x:)(?X:)(?U:)(?J:)', [
                new Literal('foo'),
                new GroupOpenFlags('i', new IdentityOptionSetting('i')), new GroupClose(),
                new GroupOpenFlags('s', new IdentityOptionSetting('s')), new GroupClose(),
                new GroupOpenFlags('m', new IdentityOptionSetting('m')), new GroupClose(),
                new GroupOpenFlags('x', new IdentityOptionSetting('x')), new GroupClose(),
                new GroupOpenFlags('X', new IdentityOptionSetting('X')), new GroupClose(),
                new GroupOpenFlags('U', new IdentityOptionSetting('U')), new GroupClose(),
                new GroupOpenFlags('J', new IdentityOptionSetting('J')), new GroupClose()
            ]]
        ], [
            'pcre2 groups #1' => ['(?X)', [new GroupOpen(''), new Literal('?X'), new GroupClose()]], // illegal in PCRE2
            'pcre2 groups #2' => ['(?n)', [new GroupRemainder('n', new IdentityOptionSetting('n'))]],
            'pcre2 groups #3' => ['foo(?UxmsiJn:)', [new Literal('foo'), new GroupOpenFlags('UxmsiJn', new IdentityOptionSetting('UxmsiJn')), new GroupClose()]],
            'pcre2 groups #4' => ['foo(?Uxm-siJn:)', [new Literal('foo'), new GroupOpenFlags('Uxm-siJn', new IdentityOptionSetting('Uxm-siJn')), new GroupClose()]],
            'pcre2 groups #5' => ['foo(?UxmsiJn)', [new Literal('foo'), new GroupRemainder('UxmsiJn', new IdentityOptionSetting('UxmsiJn'))]],
            'pcre2 groups #6' => ['foo(?Uxms-iJn)', [new Literal('foo'), new GroupRemainder('Uxms-iJn', new IdentityOptionSetting('Uxms-iJn'))]],
            'pcre2 groups #7' => ['foo(?i:)(?s:)(?m:)(?x:)(?n:)(?U:)(?J:)', [
                new Literal('foo'),
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
     * @param PatternEntities $entities
     * @param Entity[] $expected
     */
    private function assertEntitiesEqual(PatternEntities $entities, array $expected): void
    {
        $phrases = [];
        foreach ($expected as $entity) {
            $phrases[] = $entity->phrase();
        }
        $this->assertEquals($phrases, $entities->phrases());
    }

    private function assertEntitiesRepresent(PatternEntities $asEntities, string $pattern): void
    {
        $phrase = new CompositePhrase($asEntities->phrases());
        $this->assertSame($phrase->conjugated('/'), $pattern);
    }
}
