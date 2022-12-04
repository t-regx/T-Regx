<?php
namespace Test\Feature\CleanRegex\noAutoCapture;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsGroup;
use Test\Utils\Assertion\AssertsPattern;
use Test\Utils\TestCase\TestCaseConditional;
use Test\Utils\TestCase\TestCasePasses;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;
use TRegx\Pcre;

class PatternTest extends TestCase
{
    use TestCasePasses, AssertsGroup, AssertsPattern, TestCaseConditional;

    /**
     * @test
     */
    public function shouldEnableNoAutoCapture()
    {
        // given
        $pattern = Pattern::of('pattern', 'nin');
        // when
        $pattern->test('value');
        // then
        $this->pass();
    }

    /**
     * @test
     */
    public function shouldNotCapture()
    {
        // given
        $pattern = Pattern::of('(pattern)', 'n');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupTexts($matcher->first(), []);
        $this->assertGroupMissing($matcher, 1);
    }

    /**
     * @test
     */
    public function shouldNotCaptureMixedWithOtherFlags()
    {
        // given
        $pattern = Pattern::of('(pattern)', 'inm');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupMissing($matcher, 1);
    }

    /**
     * @test
     * @dataProvider namedGroupSyntaxes
     */
    public function shouldCaptureNamedGroup(string $namedGroup)
    {
        // given
        $pattern = Pattern::of("(?{$namedGroup}bar)", 'n');
        // when, then
        $this->assertConsumesFirstGroup('bar', 'bar', $pattern);
    }

    public function namedGroupSyntaxes(): array
    {
        return provided(["<name>", "'name'", "P<name>"]);
    }

    /**
     * @test
     */
    public function shouldNotCaptureUnnamedGroups()
    {
        // given
        $pattern = Pattern::of('(foo),(?<name>bar),(cat),(?<other>door)', 'n');
        // when
        $matcher = $pattern->match('foo,bar,cat,door');
        // then
        $this->assertGroupTexts($matcher->first(), ['bar', 'door', 'name' => 'bar', 'other' => 'door']);
        $this->assertGroupMissing($matcher, 3);
        $this->assertGroupMissing($matcher, 4);
    }

    /**
     * @test
     */
    public function shouldCaptureReferencesName()
    {
        // given
        $pattern = Pattern::of("(?&name)(?(DEFINE)(?<name>group))", 'n');
        // when, then
        $this->assertConsumesFirst('group', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureReferencesNameFoo()
    {
        // given
        $pattern = Pattern::of("(?&foo)(?(DEFINE)(?<foo>group))", 'n');
        // when, then
        $this->assertConsumesFirst('group', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureReferencesNameWithUnderscore()
    {
        // given
        $pattern = Pattern::of("(?&b_c)(?(DEFINE)(?<b_c>group))", 'n');
        // when, then
        $this->assertConsumesFirst('group', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureReferencesNameWithDigits()
    {
        // given
        $pattern = Pattern::of("(?&_012789)(?(DEFINE)(?<_012789>group))", 'n');
        // when, then
        $this->assertConsumesFirst('group', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureReferencesNameWithBoundryCharacters()
    {
        // given
        $pattern = Pattern::of("(?&a_z)(?(DEFINE)(?<a_z>group))", 'n');
        // when, then
        $this->assertConsumesFirst('group', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreDefinition()
    {
        // given
        $pattern = Pattern::of('(?(DEFINE)(?<name>group))', 'n');
        // when, then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?(DEFINE)(?<name>group))/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(?(DEFINE)(?<name>group))/', $pattern);
        } else {
            $this->assertPatternIs('/(?(DEFINE)(?<name>group))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionalRecursive()
    {
        // given
        $pattern = Pattern::of('A(?(R)B)(?R)?(C)', 'n');
        // when, then
        $this->assertConsumesFirst('AABCC', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/A(?(R)B)(?R)?(C)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)A(?(R)B)(?R)?(C)/', $pattern);
        } else {
            $this->assertPatternIs('/A(?(R)B)(?R)?(?:C)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionalRecursiveSubroutine()
    {
        // given
        $pattern = Pattern::of('(?<name>A(?(R1)C))(B(?1))', 'n');
        // when, then
        $this->assertConsumesFirstGroup('ABACBAC', 'A', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?<name>A(?(R1)C))(B(?1))/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(?<name>A(?(R1)C))(B(?1))/', $pattern);
        } else {
            $this->assertPatternIs('/(?<name>A(?(R1)C))(?:B(?1))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldThrowForMissingRecrusiveSubroutine()
    {
        // given
        $pattern = Pattern::of('(A(?(R1)C))(?:B(?1))', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 5');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 9');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 20');
        }
        // when
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function shouldCaptureVersion()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Version assertion is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('(?(VERSION>=8.0)true|false)', 'n');
        // when, then
        $this->assertConsumesFirst('true', $pattern);
    }

    /**
     * @test
     */
    public function shouldCapturedGroupIndeciesMatch()
    {
        // given
        $pattern = Pattern::of('(foo),(?<name>bar),(cat),(?<other>door)', 'n');
        // then
        $detail = $pattern->match('foo,bar,cat,door')->first();
        // then
        $this->assertSame(1, $detail->group(1)->index());
        $this->assertSame(2, $detail->group(2)->index());

        $this->assertSame('name', $detail->group(1)->name());
        $this->assertSame('other', $detail->group(2)->name());

        $this->assertSame(1, $detail->group('name')->index());
        $this->assertSame(2, $detail->group('other')->index());
    }

    /**
     * @test
     */
    public function shouldIgnoreAtomicGroup()
    {
        // given
        $pattern = Pattern::of('(?>foo|f)oo$', 'n');
        // when, then
        $this->assertPatternFails($pattern, 'foo');
    }

    /**
     * @test
     */
    public function shouldIgnoreNonCapturingGroup()
    {
        // given
        $pattern = Pattern::of('(?:foo),bar', 'n');
        // when, then
        $this->assertConsumesFirst('foo,bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReset()
    {
        // given
        $pattern = Pattern::of('(?|:(?<name>colon):|"(?<name>quote)")', '');
        // when, then
        $this->assertConsumesFirstGroup(':colon:', 'colon', $pattern);
        $this->assertConsumesFirstGroup('"quote"', 'quote', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreCommentGroup()
    {
        // given
        $pattern = Pattern::of('(?#foo)bar', 'n');
        // when, then
        $this->assertConsumesFirst('bar', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookAhead()
    {
        // given
        $pattern = Pattern::of('(?=foo)foo', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookAheadNegative()
    {
        // given
        $pattern = Pattern::of('(?!bar)foo', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookBehind()
    {
        // given
        $pattern = Pattern::of('foo(?<=foo)', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookBehindNegative()
    {
        // given
        $pattern = Pattern::of('foo(?<!bar)', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookAheadAlternativeNotation()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Alternative look-ahead syntax is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('(*pla:foo)foo', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookBehindAlternativeNotation()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Alternative look-behind syntax is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('foo(*plb:foo)', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookAheadAlternativeLongNotation()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Alternative look-ahead syntax is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('(*positive_lookahead:foo)foo', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookBehindAlternativeLongNotation()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Alternative look-behind syntax is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('foo(*positive_lookbehind:foo)', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreLookAheadAlternativeLongNotationForCompletness()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Verb (*positive_lookahead:) is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('(*positive_lookahead:foo)foo', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(*positive_lookahead:foo)foo/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(*positive_lookahead:foo)foo/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreLookBehindAlternativeLongNotationForCompletness()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Verb (*positive_lookbehind:) is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('(?<group>foo)(*positive_lookbehind:foo)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('foofoo', 'foo', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?<group>foo)(*positive_lookbehind:foo)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(?<group>foo)(*positive_lookbehind:foo)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreVerbWithoutName()
    {
        // given
        $pattern = Pattern::of('(*)', 'n');
        // when, then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(*)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(*)/', $pattern);
        } else {
            $this->assertPatternIs('/(*)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionalGroupReferenceMissingGroup()
    {
        // given
        $pattern = Pattern::of('(?(13456)yes|no)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 6');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 10');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 9');
        }
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionNonCapturingGroup()
    {
        // given
        $pattern = Pattern::of('(foo)?:(?(13456)yes|no)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 13');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 17');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 18');
        }
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionRelativeBefore()
    {
        // given
        $pattern = Pattern::of('(foo)?:(?(-1)yes|no)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 12');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 16');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 15');
        }
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionRelativeAfter()
    {
        // given
        $pattern = Pattern::of('(foo)?:(?(+1)yes|no)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 10');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 14');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 15');
        }
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionRelativeAfterMany()
    {
        // given
        $pattern = Pattern::of('(foo)?:(?(+3)yes|no)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 10');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 14');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 15');
        }
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     * @dataProvider namedConditionalSyntaxes
     */
    public function shouldIgnoreNamedConditional(string $syntax)
    {
        // given
        $pattern = Pattern::of("(?<name>foo)?:(?($syntax)yes|no)", 'n');
        // when, then
        $this->assertConsumesFirst(':no', $pattern);
    }

    public function namedConditionalSyntaxes(): array
    {
        return provided(['name', '<name>', "'name'"]);
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionRecursive()
    {
        // given
        $pattern = Pattern::of(':(?(R)yes|no)', 'n');
        // when, then
        $this->assertConsumesFirst(':no', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreVerb()
    {
        // given
        $pattern = Pattern::of('foo(*FAIL)', 'n');
        // when, then
        $this->assertPatternFails($pattern, 'foo');
    }

    /**
     * @test
     * @dataProvider ignored
     */
    public function shouldIgnore(string $expression)
    {
        // given
        $pattern = Pattern::of("foo $expression", 'n');
        // when
        $delimited = $pattern->delimited();
        // then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertSame("/foo $expression/n", $delimited);
        } else if (Pcre::pcre2()) {
            $this->assertSame("/(?n)foo $expression/", $delimited);
        } else {
            $this->assertSame("/foo $expression/", $delimited);
        }
    }

    public function ignored(): array
    {
        return provided([
            '(?(+2)',
            '(?(-2)',
            '(?(<name>)',
            "(?('name')",
            '(?(name)',
            '(?(R)',
            '(?(R&name)',
            '(?(DEFINE)',
            '(?(VERSION[>]=n.m)',
            '(*ACCEPT)',
            '(*FAIL)',
            '(*MARK:NAME)',
            '(*COMMIT)',
            '(*PRUNE)',
            '(*SKIP)',
            '(*SKIP:NAME)',
            '(*MARK:NAME)',
            '(*PRUNE:NAME)',
            '(*THEN)',
        ]);
    }

    /**
     * @test
     */
    public function shouldIgnoreInnerOptionSetting()
    {
        // given
        $pattern = Pattern::of('foo:(?i:(FOO))', 'n');
        // when, then
        $this->assertConsumesFirst('foo:foo', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 1);
    }

    /**
     * @test
     */
    public function shouldIgnoreInnerOptionUnsetting()
    {
        // given
        $pattern = Pattern::of('(?-U:(fo+))', 'Un');
        // when, then
        $this->assertConsumesFirst('foooo', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 1);
    }

    /**
     * @test
     */
    public function shouldIgnoreInnerOptionUnsettingWildcard()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Modifier reset is only available in PCRE2');
        }
        // given
        $pattern = Pattern::of('foo:(?^:#foo)', 'xn');
        // when, then
        $this->assertConsumesFirst('foo:#foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSetting()
    {
        // given
        $pattern = Pattern::of('foo:(?i)FOO', 'n');
        // when, then
        $this->assertConsumesFirst('foo:foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionUnsetting()
    {
        // given
        $pattern = Pattern::of('foo:(?-x)#foo', 'xn');
        // when, then
        $this->assertConsumesFirst('foo:#foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionUnsettingWildcard()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary('Modifier reset is available in PCRE2');
        }
        // given
        $pattern = Pattern::of('foo:(?^)#foo', 'xn');
        // when, then
        $this->assertConsumesFirst('foo:#foo', $pattern);
    }

    /**
     * @test
     * @dataProvider groupReferencesSyntaxes
     */
    public function shouldIgnoreGroupReference(string $reference)
    {
        // given
        $emptyGroups = str_repeat('(?<padding>one)?', 13);
        $pattern = Pattern::of("(?<name>one):$reference $emptyGroups", 'xnJ');
        // when, then
        $this->assertConsumesFirst('one:one', $pattern);
    }

    public function groupReferencesSyntaxes(): array
    {
        return provided([
            '(?1)',
            '(?2)',
            '(?3)',
            '(?9)',
            '(?P=name)',
        ]);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceMalformed()
    {
        // given
        $pattern = Pattern::of('(?p=name)', 'n');
        // when, then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?p=name)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(?p=name)/', $pattern);
        } else {
            $this->assertPatternIs('/(?p=name)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceRelativeAfter()
    {
        // given
        $pattern = Pattern::of('(?+1):(?<name>one)', 'n');
        // when, then
        $this->assertConsumesFirst('one:one', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceRelativeManyAfter()
    {
        // given
        $groups = str_repeat('(?<name>)', 18);
        $pattern = Pattern::of("(?+19)$groups:(?<name>one)", 'nJ');
        // when, then
        $this->assertConsumesFirst('one:one', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceRelativeBefore()
    {
        // given
        $pattern = Pattern::of('(?<name>one):(?-1)', 'n');
        // when, then
        $this->assertConsumesFirst('one:one', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceMissingGroup()
    {
        // given
        $pattern = Pattern::of('(?13456)', '');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Reference to non-existent subpattern at offset 7');
        // when
        $pattern->test(':no');
    }

    /**
     * @test
     */
    public function shouldIgnoreRecursivePattern()
    {
        // given
        $pattern = Pattern::of('"(?:(?R)|foo)"', 'n');
        // when, then
        $this->assertConsumesFirst('""""foo""""', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupReferenceAndThrowForMissingGroup()
    {
        // given
        $pattern = Pattern::of('(one):(?1)', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 9');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 13');
        } else {
            $this->expectExceptionMessage('Reference to non-existent subpattern at offset 11');
        }
        // when, then
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function shouldIgnoreParenthesisInCharacterClass()
    {
        // given
        $pattern = Pattern::of('[ ()]{2,}', 'n');
        // when, then
        $this->assertPatternFails($pattern, '(?:)');
        $this->assertConsumesFirst('()', $pattern);
    }

    /**
     * @test
     */
    public function shouldNotCaptureAfterEscapedOpeningBracked()
    {
        // given
        $pattern = Pattern::of('\[(foo)\]', 'n');
        // when
        $matcher = $pattern->match('[foo]');
        // when, then
        $this->assertGroupMissing($matcher, 1);
        $this->assertConsumesFirst('[foo]', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreEscapedParenthesis()
    {
        // given
        $pattern = Pattern::of('\(\)', 'n');
        // when, then
        $this->assertPatternFails($pattern, '(:)');
        $this->assertConsumesFirst('()', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreParenthesisInQuote()
    {
        // given
        $pattern = Pattern::of('\Q()\E', 'n');
        // when, then
        $this->assertPatternFails($pattern, '(?:)');
        $this->assertConsumesFirst('()', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreNullGroup()
    {
        // given
        $pattern = Pattern::of('"(?:)"', 'n');
        // when, then
        $this->assertConsumesFirst('""', $pattern);
    }

    /**
     * @test
     */
    public function shouldNotCaptureInAtomicGroup()
    {
        // given
        $pattern = Pattern::of('(?>(foo))', 'n');
        // when, then
        $this->assertConsumesFirst('foo', $pattern);
        $this->assertGroupMissing($pattern->match('foo'), 1);
    }

    /**
     * @test
     */
    public function shouldNotCaptureOuterOptionSetting()
    {
        // given
        $pattern = Pattern::of('(?n)(pattern)');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupTexts($matcher->first(), []);
        $this->assertGroupMissing($matcher, 1);
    }

    /**
     * @test
     */
    public function shouldNotCaptureInnerOptionSetting()
    {
        // given
        $pattern = Pattern::of('(?n:(pattern))');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupTexts($matcher->first(), []);
        $this->assertGroupMissing($matcher, 1);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n:(pattern))/', $pattern);
        } else {
            $this->assertPatternIs('/(?:(?:pattern))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInnerOptionSettingMixed()
    {
        // given
        $pattern = Pattern::of('(?inm:(pattern))');
        // when
        $matcher = $pattern->match('pattern');
        // then
        $this->assertGroupMissing($matcher, 1);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?inm:(pattern))/', $pattern);
        } else {
            $this->assertPatternIs('/(?im:(?:pattern))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInnerOptionSettingSetBeforeUnset()
    {
        // given
        $pattern = Pattern::of('(?n-m:(foo))');
        // when
        $matcher = $pattern->match('foo');
        // when, then
        $this->assertGroupMissing($matcher, 1);
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n-m:(foo))/', $pattern);
        } else {
            $this->assertPatternIs('/(?-m:(?:foo))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionUnsetting()
    {
        // given
        $pattern = Pattern::of('(?-n)(pattern)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('pattern', 'pattern', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionUnsettingAfterSetting()
    {
        // given
        $pattern = Pattern::of('(?n-n)(pattern)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('pattern', 'pattern', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionUnsettingThreeHyphens()
    {
        if (Pcre::pcre2()) {
            $this->markTestUnnecessary("Multiple hyphens aren't legal in PCRE2");
        }
        // given
        $pattern = Pattern::of('(?---n)(pattern)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('pattern', 'pattern', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionUnsetting()
    {
        // given
        $pattern = Pattern::of('(?-n:(pattern))', 'n');
        // when, then
        $this->assertConsumesFirstGroup('pattern', 'pattern', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionUnsettingThreeHyphens()
    {
        if (Pcre::pcre2()) {
            $this->markTestUnnecessary("Multiple hyphens aren't legal in PCRE2");
        }
        // given
        $pattern = Pattern::of('(?---n:(pattern))', 'n');
        // when, then
        $this->assertConsumesFirstGroup('pattern', 'pattern', $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionUnsettingMany()
    {
        // given
        $pattern = Pattern::of("(?is-nm)(a#com\n.b)", 'n');
        // when, then
        $this->assertConsumesFirstGroup("A#com\n\nB", "A#com\n\nB", $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionUnsettingMany()
    {
        // given
        $pattern = Pattern::of("(?is-mn:(a.b))", 'nm');
        // when, then
        $this->assertConsumesFirstGroup("A\nB", "A\nB", $pattern);
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionSettingUnsettingMany()
    {
        // given
        $pattern = Pattern::of('(?-Un:(fo+))', 'Un');
        // when, then
        $this->assertConsumesFirstGroup('foooo', 'foooo', $pattern);
    }

    /**
     * @test
     */
    public function shouldOuterOption_EffectivelyEmpty_stillCauseMalformedPattern()
    {
        // given
        $pattern = Pattern::of('(?n)?');
        // then
        $this->expectException(MalformedPatternException::class);
        if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 4');
        } else {
            $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 3');
        }
        // when
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function shouldInnerOption_EffectivelyEmpty_notFallBackToPrevious()
    {
        // given
        $pattern = Pattern::of('<(?n:){0}>');
        // when, then
        $this->assertConsumesFirst('<>', $pattern);
        $this->assertPatternFails($pattern, '>');
    }

    /**
     * @test
     */
    public function shouldDoubleUnsetNegativeThrowMalformedPattern()
    {
        if (!Pcre::pcre2()) {
            $this->markTestUnnecessary("Double hyphens aren't invalid in PCRE");
        }
        // given
        $pattern = Pattern::of('(?-n-n)');
        // then
        $this->expectException(MalformedPatternException::class);
        // when
        $pattern->test('foo');
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionReset()
    {
        // given
        $pattern = Pattern::of('(?^)(foo)', 'n');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirstGroup('foo', 'foo', $pattern);
        } else {
            $this->assertPatternIs('/(?^)(foo)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionReset()
    {
        // given
        $pattern = Pattern::of('(?^:(foo))', 'n');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirstGroup('foo', 'foo', $pattern);
        } else {
            $this->assertPatternIs('/(?^:(foo))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldCaptureOuterOptionResetMixed()
    {
        // given
        $pattern = Pattern::of('(?^m)(foo)', 'n');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirstGroup('foo', 'foo', $pattern);
        } else {
            $this->assertPatternIs('/(?^m)(foo)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldCaptureInnerOptionResetMixed()
    {
        // given
        $pattern = Pattern::of('(?^m:(foo))', 'n');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirstGroup('foo', 'foo', $pattern);
        } else {
            $this->assertPatternIs('/(?^m:(foo))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureInnerOptionSettingAfterReset()
    {
        // given
        $pattern = Pattern::of('(?^n:(foo))');
        // when
        $matcher = $pattern->match('foo');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertGroupMissing($matcher, 1);
            $this->assertPatternIs('/(?^n:(foo))/', $pattern);
        } else {
            $this->assertPatternIs('/(?^:(?:foo))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldNotCaptureOuterOptionSettingAfterReset()
    {
        // given
        $pattern = Pattern::of('(?^n)(foo)');
        // when
        $matcher = $pattern->match('foo');
        // when, then
        if (Pcre::pcre2()) {
            $this->assertGroupMissing($matcher, 1);
        }
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?^n)(foo)/', $pattern);
        } else {
            $this->assertPatternIs('/(?^)(?:foo)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingExtendedNoAutoCapture()
    {
        // given
        $pattern = Pattern::inject('(?xn)(foo:@)', ['value']);
        // when, then
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?xn)(foo:(?>value))/', $pattern);
        } else {
            $this->assertPatternIs('/(?x)(?:foo:(?>value))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingNoAutoCaptureExtended()
    {
        // given
        $pattern = Pattern::inject('(?nx)(foo:@)', ['value']);
        // when, then
        if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?nx)(foo:(?>value))/', $pattern);
        } else {
            $this->assertPatternIs('/(?x)(?:foo:(?>value))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingResetExtendedNoAutoCapture()
    {
        // given
        $pattern = Pattern::inject('(?^xn)(foo:@)', ['value']);
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirst('foo:value', $pattern);
            $this->assertPatternIs('/(?^xn)(foo:(?>value))/', $pattern);
        } else {
            $this->assertPatternIs('/(?^x)(?:foo:(?>value))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreOuterOptionSettingResetNoAutoCaptureExtended()
    {
        // given
        $pattern = Pattern::inject('(?^nx)(foo:@)', ['value']);
        // when, then
        if (Pcre::pcre2()) {
            $this->assertConsumesFirst('foo:value', $pattern);
            $this->assertPatternIs('/(?^nx)(foo:(?>value))/', $pattern);
        } else {
            $this->assertPatternIs('/(?^x)(?:foo:(?>value))/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreEmptyNonCapturingGroup()
    {
        // given
        $pattern = Pattern::of('(?:)', 'n');
        // when, then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(?:)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(?:)/', $pattern);
        } else {
            $this->assertPatternIs('/(?:)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreGroupAtomic()
    {
        // given
        $pattern = Pattern::of('(*atomic:)', 'n');
        // when, then
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(*atomic:)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(?n)(*atomic:)/', $pattern);
        } else {
            $this->assertPatternIs('/(*atomic:)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionalLookAhead()
    {
        // given
        $pattern = Pattern::of('(?(?=foo)foor|bar)', 'n');
        // when
        $this->assertConsumesFirst('foor', $pattern);
    }

    /**
     * @test
     */
    public function shouldIgnoreConditionalLookBehind()
    {
        // given
        $pattern = Pattern::of('start:(?(?<=start:)(?<group>foo))', 'n');
        // when
        $this->assertConsumesFirstGroup('start:foor', 'foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldOptionSettingTakePrecedence()
    {
        // given
        $pattern = Pattern::of('(?-n)(foo)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('foo', 'foo', $pattern);
    }

    /**
     * @test
     */
    public function shouldAcceptPcreVerbs()
    {
        // given
        $pattern = Pattern::of('(*CRLF)(foo),(?<name>bar)', 'n');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(*CRLF)(foo),(?<name>bar)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(*CRLF)(?n)(foo),(?<name>bar)/', $pattern);
        } else {
            $this->assertPatternIs('/(*CRLF)(?:foo),(?<name>bar)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldAcceptPcreVerbsInject()
    {
        // given
        $pattern = Pattern::inject('(*CRLF)(foo),(?<name>bar)', [], 'n');
        // when, then
        $this->assertConsumesFirstGroup('foo,bar', 'bar', $pattern);
        if (PHP_VERSION_ID >= 80200) {
            $this->assertPatternIs('/(*CRLF)(foo),(?<name>bar)/n', $pattern);
        } else if (Pcre::pcre2()) {
            $this->assertPatternIs('/(*CRLF)(?n)(foo),(?<name>bar)/', $pattern);
        } else {
            $this->assertPatternIs('/(*CRLF)(?:foo),(?<name>bar)/', $pattern);
        }
    }

    /**
     * @test
     */
    public function shouldThrowForMalformedPattern()
    {
        // given
        $pattern = Pattern::of('+', 'n');
        // then
        $this->expectException(MalformedPatternException::class);
        if (PHP_VERSION_ID >= 80200) {
            $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        } else if (Pcre::pcre2()) {
            $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 4');
        } else {
            $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        }
        // when
        $pattern->test('foo');
    }
}
