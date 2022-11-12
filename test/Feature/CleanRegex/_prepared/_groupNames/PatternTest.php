<?php
namespace Test\Feature\CleanRegex\_prepared\_groupNames;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldThrowForGroupName_Placeholder()
    {
        // given
        $pattern = Pattern::inject('(?<@>)', []);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Subpattern name expected at offset 3');
        // when
        $pattern->test('Bar');
    }

    /**
     * @test
     * @dataProvider namedGroups
     */
    public function shouldNotInjectIntoNamedGroup(string $namedGroup)
    {
        // given
        $pattern = Pattern::inject($namedGroup, []);
        // when
        $this->assertDelimited("/$namedGroup/", $pattern);
    }

    public function namedGroups(): array
    {
        return \named([
            'placeholder' => [
                ['(?<@>)'],
                ["(?'@')"],
                ["(?P<@>)"],
            ],

            'placeholder x3' => [
                ['(?<@@@>)'],
                ["(?'@@@')"],
                ["(?P<@@@>)"],
            ],

            'placeholder letter placeholder' => [
                ['(?<@name_049@>)'],
                ["(?'@name_049@')"],
                ["(?P<@name_049@>)"],
            ],

            'letters' => [
                ['(?<b4c@>)'],
                ["(?'b4c@')"],
                ["(?P<b4c@>)"],
            ],

            'uppercase' => [
                ['(?<AGZ@>)'],
                ["(?'AGZ@')"],
                ["(?P<AGZ@>)"],
            ],

            'digits' => [
                ['(?<12309@>)'],
                ["(?'12309@')"],
                ["(?P<12309@>)"],
            ],

            'border letters' => [
                ['(?<az@>)'],
                ["(?'az@')"],
                ["(?P<az@>)"],
                ['(?<abcdefghijklmnopqrstuvwxyz@>)'],
            ],

            'unclosed' => [
                ['(?<'],
                ['(?<>'],
                ["(?''"],
                ['(?<@'],
                ['(?<foo@'],
                ["(?'foo@"],
                ["(?P<foo@"],
            ],

            'underscore permutations' => [
                ['(?<_group@>)'],
                ['(?<_@>)'],
                ['(?<gro_up@>)'],
            ],

            'empty' => [
                ["(?<>)"],
            ]
        ]);
    }

    /**
     * @test
     * @dataProvider groupNames
     */
    public function shouldNotInjectIntoGroupName(string $namedGroup)
    {
        // given
        $pattern = Pattern::inject("(?<$namedGroup@>)", []);
        // when
        $this->assertDelimited("/(?<$namedGroup@>)/", $pattern);
    }

    public function groupNames(): array
    {
        return \named([
            ['group'],
            ['_group'],
            ['GROUP'],
            ['g'],
            ['a123_'],
            ['a0'],
            ['a9'],
            ['ó'],
            ['gróup'],
            ['wordß'],
            ['ßark'],
            ['Ĝ'],
            ['Ħ'],
            ['ʷ'],
            ['ƻ'],
            ['ǅ'],
            ['foo_ßark'],
            ['foo_Ĝ'],
            ['foo_Ħ'],
            ['foo_ʷ'],
            ['foo_ƻ'],
            ['foo_ǅ'],
            ['foo_٤'],
            ['foo_Ⅴ'],
            ['foo_¼'],
            ['foo_À'],
            ['foo_Á'],

            ["o\xc3"], // first unicode byte of "ó"
            ["o\xb3"], // second unicode byte of "ó"
            [chr(195)], // first unicode byte of "ó"
            [chr(179)], // second unicode byte of "ó"
            [chr(254)],
            [chr(255)],
        ]);
    }

    /**
     * @test
     */
    public function shouldThrowForPythonApostrophesAsGroupName()
    {
        // given
        $pattern = Pattern::inject("(?P'@')", ['value']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Unrecognized character after (?P at offset 3');
        // when
        $pattern->test('value');
    }

    /**
     * @test
     */
    public function shouldThrowForLowercasePythonGroupName()
    {
        // given
        $pattern = Pattern::inject("(?p<@>)", ['value']);
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Unrecognized character after (? or (?- at offset 2');
        // when
        $pattern->test('value');
    }

    /**
     * @test
     */
    public function shouldNotTreatPythonApostrophesAsGroupName()
    {
        // given
        $pattern = Pattern::inject("(?P'@')", ['value']);
        // when
        $this->assertDelimited("/(?P'(?>value)')/", $pattern);
    }

    /**
     * @test
     * @dataProvider placeholdersAfterNamedGroups
     */
    public function shouldInjectAfterGroupName(string $template, string $expected)
    {
        // given
        $pattern = Pattern::inject($template, ['value']);
        // when
        $this->assertDelimited("/$expected/", $pattern);
    }

    public function placeholdersAfterNamedGroups(): array
    {
        return \named([
            'placeholder' => [
                ['(?<name>@)', '(?<name>(?>value))'],
                ["(?'name'@)", "(?'name'(?>value))"],
                ["(?P<name>@)", "(?P<name>(?>value))"],

                ['(?<@>@)', '(?<@>(?>value))'],
                ["(?'@'@)", "(?'@'(?>value))"],
                ["(?P<@>@)", "(?P<@>(?>value))"],
            ],

            'tab'              => ["(?<name\t@", "(?<name\t(?>value)"],
            'newline'          => ["(?<name\n@", "(?<name\n(?>value)"],
            'vertical tab'     => ["(?<name\v@", "(?<name\v(?>value)"],
            'form feed'        => ["(?<name\f@", "(?<name\f(?>value)"],
            'hash'             => ['(?<name#@', '(?<name#(?>value)'],
            'bracket <'        => ['(?<name<@', '(?<name<(?>value)'],
            'bracket ('        => ['(?<name(@', '(?<name((?>value)'],
            'bracket )'        => ['(?<name)@', '(?<name)(?>value)'],
            'bracket ['        => ['(?<name\[@', '(?<name\[(?>value)'],
            'bracket ]'        => ['(?<name]@', '(?<name](?>value)'],
            'bracket }'        => ['(?<name}@', '(?<name}(?>value)'],
            'bracket {'        => ['(?<name{@', '(?<name{(?>value)'],
            'pipe |'           => ['(?<name|@', '(?<name|(?>value)'],
            'period'           => ['(?<name.@', '(?<name.(?>value)'],
            'comma'            => ['(?<name,@', '(?<name,(?>value)'],
            'question mark'    => ['(?<name?@', '(?<name?(?>value)'],
            'exclamation mark' => ['(?<name!@', '(?<name!(?>value)'],
            'asterisk'         => ['(?<name*@', '(?<name*(?>value)'],
            'ampersand'        => ['(?<name&@', '(?<name&(?>value)'],
            'plus'             => ['(?<name+@', '(?<name+(?>value)'],
            'equals'           => ['(?<name=@', '(?<name=(?>value)'],
            'colon'            => ['(?<name:@', '(?<name:(?>value)'],
            'semicolon'        => ['(?<name;@', '(?<name;(?>value)'],
            'hyphen'           => ['(?<name-@', '(?<name-(?>value)'],
            'caret'            => ['(?<name^@', '(?<name^(?>value)'],
            'dollar'           => ['(?<name$@', '(?<name$(?>value)'],
            'percent'          => ['(?<name%@', '(?<name%(?>value)'],
            'tilde'            => ['(?<name~@', '(?<name~(?>value)'],
            'backtick'         => ['(?<name`@', '(?<name`(?>value)'],
            'quote'            => ['(?<name"@', '(?<name"(?>value)'],
            'null byte'        => ["(?<name\0@", "(?<name\0(?>value)"],
            'escape'           => ["(?<name\x1B@", "(?<name\x1B(?>value)"],
            'delete'           => ["(?<name\x7F@", "(?<name\x7F(?>value)"],
        ]);
    }

    /**
     * @test
     */
    public function shouldInjectAfterNamedGroup_Slash()
    {
        // given
        $pattern = Pattern::inject('(?<name/@', ['value']);
        // when
        $this->assertDelimited("#(?<name/(?>value)#", $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterNamedGroup_Backslash()
    {
        // given
        $pattern = Pattern::inject('(?<nam\e@', ['value']);
        // when
        $this->assertDelimited('/(?<nam\e(?>value)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterAtomicGroup()
    {
        // given
        $pattern = Pattern::inject('(?>@', ['value']);
        // when
        $this->assertDelimited('/(?>(?>value)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterEmptyGroupName()
    {
        // given
        $pattern = Pattern::inject('(?<>@', ['mark']);
        // when
        $this->assertDelimited('/(?<>(?>mark)/', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterNamedGroup()
    {
        // given
        $pattern = Pattern::inject('(?<name@>)@', ['twain']);
        // when
        $this->assertDelimited("/(?<name@>)(?>twain)/", $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterGroupClosingParenthesis()
    {
        // given
        $pattern = Pattern::inject('(?<@)@', ['john']);
        // when
        $this->assertDelimited("/(?<@)(?>john)/", $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectAfterSpaceInGroupName()
    {
        // given
        $pattern = Pattern::inject('(?<@ @)', ['james']);
        // when
        $this->assertDelimited("/(?<@ (?>james))/", $pattern);
    }

    private function assertDelimited(string $expected, Pattern $pattern): void
    {
        $this->assertSame($expected, $pattern->delimited());
    }

    /**
     * @test
     * @dataProvider malformedUnicodeCharacters
     */
    public function shouldInvalidUnicodeGroupName_notPoluteUserSpace(string $groupName)
    {
        // given
        $pattern = Pattern::inject("(?<$groupName>foo)", []);
        // when
        try {
            $pattern->test('Foo');
        } catch (MalformedPatternException $silenced) {
        }
        // when
        $this->assertSame(null, \error_get_last());
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }

    public function malformedUnicodeCharacters(): array
    {
        return \named([
            ["o\xc3"], // first unicode byte of "ó"
            ["o\xb3"], // second unicode byte of "ó"
            [chr(195)], // first unicode byte of "ó"
            [chr(179)], // second unicode byte of "ó"
            [chr(254)],
            [chr(255)],
        ]);
    }
}
