<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Replace\ReferencesReplacer;

class ReferencesReplacerTest extends TestCase
{
    /**
     * @test
     * @dataProvider subjects
     * @param string $pattern
     * @param string $subject
     * @param string $replacement
     * @param array $groups
     * @param string $expected
     */
    public function shouldReplace(string $pattern, string $subject, string $replacement, array $groups, string $expected): void
    {
        // when
        $result = ReferencesReplacer::replace($replacement, $groups);

        // then
        $this->validateContract($pattern, $subject, $replacement, $expected);
        $this->assertEquals($expected, $result);
    }

    public function subjects(): array
    {
        $l = str_repeat('()', 10);
        return [
            // Basic
            ['/(Bar)/', 'Bar', 'Foo:\0', [0 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:$0', [0 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:${0}', [0 => 'Bar'], 'Foo:Bar'],

            ['/(Bar)/', 'Bar', 'Foo:\1', [1 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:$1', [1 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:${1}', [1 => 'Bar'], 'Foo:Bar'],

            // Two digit
            ["/$l(Bar)/", 'Bar', 'Foo:\11', [11 => 'Bar'], 'Foo:Bar'],
            ["/$l(Bar)/", 'Bar', 'Foo:$11', [11 => 'Bar'], 'Foo:Bar'],
            ["/$l(Bar)/", 'Bar', 'Foo:${11}', [11 => 'Bar'], 'Foo:Bar'],

            // Three digits
            ["/$l(Bar)/", 'Bar', 'Foo:\111', [11 => 'Bar'], 'Foo:Bar1'],
            ["/$l(Bar)/", 'Bar', 'Foo:$111', [11 => 'Bar'], 'Foo:Bar1'],
            ["/$l(Bar)/", 'Bar', 'Foo:${111}', [111 => 'Bar'], 'Foo:${111}'],

            // Superfluous
            ['/(Bar)/', 'Bar', 'Foo:${1}1', [1 => 'Bar'], 'Foo:Bar1'],
            ["/$l(Bar)/", 'Bar', 'Foo:${11}1', [11 => 'Bar'], 'Foo:Bar1'],

            // Padding zeros
            ['/(Bar)/', 'Bar', 'Foo:\00', [0 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:$00', [0 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:${00}', [0 => 'Bar'], 'Foo:Bar'],

            ['/(Bar)/', 'Bar', 'Foo:\01', [1 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:$01', [1 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:${01}', [1 => 'Bar'], 'Foo:Bar'],
            ['/(Bar)/', 'Bar', 'Foo:\010', [1 => 'Bar'], 'Foo:Bar0'],
            ['/(Bar)/', 'Bar', 'Foo:$010', [1 => 'Bar'], 'Foo:Bar0'],

            // Ignored
            ['/(Bar)/', 'Bar', '\\1', [1 => 'Bar'], 'Bar'],
            ['/(Bar)/', 'Bar', '\\\\1', [1 => 'Bar'], '\\1'],
            ['/(Bar)/', 'Bar', '\\\\\\1', [1 => 'Bar'], '\\Bar'],
            ['/(Bar)/', 'Bar', '\\\\\\\\1', [1 => 'Bar'], '\\\\1'],

            ['/(Bar)/', 'Bar', '\\\\\\', [], '\\\\'],
            ['/(Bar)/', 'Bar', '\\\\\\a', [], '\\\a'],

            ["/$l(Bar)/", 'Bar', 'Foo:${111}1', [111 => 'Bar'], 'Foo:${111}1'],
            ['/(Bar)/', 'Bar', 'Foo:${010}', [10 => 'Bar'], 'Foo:${010}'],

            ['/(Bar)/', 'Bar', 'Foo:${}', ['Bar'], 'Foo:${}'],
            ['/(Bar)/', 'Bar', 'Foo:\\', ['Bar'], 'Foo:\\'],
            ['/(Bar)/', 'Bar', 'Foo:\\\\4', [4 => 'Bar'], 'Foo:\\4'],
            ['/(Bar)/', 'Bar', 'Foo$$', ['Bar'], 'Foo$$'],
        ];
    }

    private function validateContract(string $pattern, string $subject, string $replacement, string $expected): void
    {
        // when
        $result = preg_replace($pattern, $replacement, $subject);

        // then
        $this->assertEquals($expected, $result, "Failed to assert that $replacement matches the contract");
    }

    /**
     * @test
     */
    public function shouldInterpretGroupsAsInteger(): void
    {
        // then
        $this->expectException(InternalCleanRegexException::class);

        //
        ReferencesReplacer::replace('\00', ['00' => 'Bar']);
    }

    /**
     * @test
     */
    public function shouldThrowForUnknownGroup(): void
    {
        // then
        $this->expectException(InternalCleanRegexException::class);

        // when
        ReferencesReplacer::replace('\4', ['Bar']);
    }
}
