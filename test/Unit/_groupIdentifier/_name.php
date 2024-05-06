<?php
namespace Test\Unit\_groupIdentifier;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _name extends TestCase
{
    /**
     * @test
     * @dataProvider validNames
     */
    public function validName(string $group)
    {
        $pattern = new Pattern('pattern');
        $this->assertFalse($pattern->groupExists($group));
    }

    /**
     * @test
     * @dataProvider invalidNames
     */
    public function invalidName(string $invalidName)
    {
        $pattern = new Pattern('pattern');
        catching(fn() => $pattern->groupExists($invalidName))
            ->assertException(InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '$invalidName'.");
    }

    /**
     * @test
     * @dataProvider invalidNames
     */
    public function expectInvalidNames(string $invalidName)
    {
        catching(fn() => new Pattern("Foo (?<$invalidName>)"))
            ->assertException(SyntaxException::class);
    }

    public function validNames(): DataProvider
    {
        return DataProvider::join($this->validAsciiNames(), $this->validUnicodeNames());
    }

    public function invalidNames(): DataProvider
    {
        return DataProvider::join($this->invalidAsciiNames(), $this->invalidUnicodeNames());
    }

    private function validAsciiNames(): DataProvider
    {
        return DataProvider::list(
            'group', '_group',
            'AA', 'ZZ', 'AGZ',
            'g',
            'a059_',
            \str_repeat('a', 32));
    }

    private function invalidAsciiNames(): DataProvider
    {
        return DataProvider::list('',
            '2group', '2_group', '2',
            'group space',
            \str_repeat('a', 33),
            '_' . \str_repeat('a', 32));
    }

    public function validUnicodeNames(): DataProvider
    {
        return DataProvider::dictionary([
            'first letter lowercase' => 'ą_group',
            'first letter uppercase' => 'Ą_group',
            'first letter titlecase' => 'ǅ_group',
            'first letter modifier'  => 'ʰ_group',
            'first letter other'     => '中_group',

            'end letter lowercase' => 'group_ą',
            'end letter uppercase' => 'group_Ą',
            'end letter titlecase' => 'group_ǅ',
            'end letter modifier'  => 'group_ʰ',
            'end letter other'     => 'group_中',

            'end number decimal' => 'group_੧',
        ]);
    }

    public function invalidUnicodeNames(): DataProvider
    {
        return DataProvider::dictionary([
            'first number letter' => 'Ⅰ_group',
            'first number other'  => '½_group',
            'first euro'          => '€_group',
            'end number letter'   => 'group_Ⅰ',
            'end number other'    => 'group_½',
            'end euro'            => 'group_€',
        ]);
    }

    /**
     * @test
     * @dataProvider nonPrintables
     */
    public function nonPrintable(string $group, string $expected)
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists("invalid $group"))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: 'invalid $expected'.");
    }

    public function nonPrintables(): DataProvider
    {
        return DataProvider::of([
            'low ascii codes' => ["\1\2\3\4", ''],
            'bell'            => [\chr(7), ''],
            'backspace'       => [\chr(8), ' '],
            'tab'             => [\chr(9), ' '],
            'newline'         => [\chr(10), ' '],
            'vertical tab'    => [\chr(11), ''],
            'form feed'       => [\chr(12), ''],
            'carriage return' => [\chr(13), ' '],
            'shift out'       => [\chr(14), ''],
        ]);
    }

    /**
     * @test
     */
    public function malformedUnicode()
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists("\xc3\x28"))
            ->assertException(\InvalidArgumentException::class)
            ->assertMessage("Group name must be an alphanumeric string, not starting with a digit, given: '\xc3\x28'.");
    }

    /**
     * @test
     */
    public function malformedUnicodeNoLastError()
    {
        $pattern = new Pattern('any');
        catching(fn() => $pattern->groupExists("\xc3\x28"));
        $this->assertSame(\PREG_NO_ERROR, \preg_last_error());
    }
}
