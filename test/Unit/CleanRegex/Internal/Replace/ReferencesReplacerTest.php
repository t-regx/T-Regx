<?php
namespace Test\Unit\TRegx\CleanRegex\Internal\Replace;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\Replace\ReferencesReplacer;

/**
 * @covers \TRegx\CleanRegex\Internal\Replace\ReferencesReplacer
 */
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
        $this->assertSame($expected, $result);
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

            // Missing group
            ['/(Bar)/', 'Bar', 'Foo:$2', [], 'Foo:'],
            ['/(Bar)/', 'Bar', '\4', [], ''],

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
        $this->assertSame($expected, $result, "Failed to assert that $replacement matches the contract");
    }

    /**
     * @test
     */
    public function shouldInterpretGroupsAsInteger(): void
    {
        // when
        $replaced = ReferencesReplacer::replace('\00', ['00' => 'Bar', '0' => 'Foo']);

        // then
        $this->assertSame('Foo', $replaced);
    }

    /**
     * @test
     * @dataProvider unicodeGroupReferences
     * @param string $subject
     */
    public function shouldBeIrrelevantOfPhpLocale(string $subject): void
    {
        // when
        $result = ReferencesReplacer::replace($subject, [
            '٠' => '0',
            '١' => '1',
            '٢' => '2',
        ]);

        // then
        $this->assertSame($subject, $result);
    }

    public function unicodeGroupReferences(): array
    {
        return [
            ['$٠$١$٢'],
            ['\٠\١\٢'],
            ['${٢}$'],
        ];
    }
}
