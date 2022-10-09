<?php
namespace Test\Feature\CleanRegex\_prepared\inject\characterClass;

use PHPUnit\Framework\TestCase;
use Test\Utils\Assertion\AssertsPattern;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\Exception\PregMalformedPatternException;

class PatternTest extends TestCase
{
    use AssertsPattern;

    /**
     * @test
     */
    public function shouldInjectIntoImmediatelyClosedCharacterClass()
    {
        // when
        $pattern = Pattern::inject('[]@]', []);
        // then
        $this->assertPatternIs('/[]@]/', $pattern);
    }

    /**
     * @test
     */
    public function shouldInjectIntoImmediatelyClosedCharacterClassTest()
    {
        // when
        $pattern = Pattern::inject('^[]@]{2}$', []);
        // then
        $this->assertTrue($pattern->test(']@'));
    }

    /**
     * @test
     * @dataProvider posixClasses
     */
    public function shouldInjectIntoPosix_WithNestedCharacterClass(string $pattern, string $delimited, string $subject)
    {
        // when
        $pattern = Pattern::inject($pattern, []);
        // then
        $this->assertPatternIs($delimited, $pattern);
    }

    /**
     * @test
     * @dataProvider posixClasses
     */
    public function shouldInjectIntoCharacterClass_WithPosixClass(string $pattern, string $delimited, string $subject)
    {
        // when
        $pattern = Pattern::inject($pattern, []);
        // then
        $this->assertTrue($pattern->test($subject));
    }

    public function posixClasses(): array
    {
        return [
            '[:alpha:]'  => ['[[:alpha:]@]{2}', '/[[:alpha:]@]{2}/', 'a@'],
            '[:alnum:]'  => ['[[:alnum:]@]{2}', '/[[:alnum:]@]{2}/', 'a@'],
            '[:ascii:]'  => ['[[:ascii:]@]{2}', '/[[:ascii:]@]{2}/', 'a@'],
            '[:blank:]'  => ['[[:blank:]@]{2}', '/[[:blank:]@]{2}/', ' @'],
            '[:cntrl:]'  => ['[[:cntrl:]@]{2}', '/[[:cntrl:]@]{2}/', "\t@"],
            '[:digit:]'  => ['[[:digit:]@]{2}', '/[[:digit:]@]{2}/', '9@'],
            '[:graph:]'  => ['[[:graph:]@]{2}', '/[[:graph:]@]{2}/', 'a@'],
            '[:lower:]'  => ['[[:lower:]@]{2}', '/[[:lower:]@]{2}/', 'a@'],
            '[:upper:]'  => ['[[:upper:]@]{2}', '/[[:upper:]@]{2}/', 'A@'],
            '[:print:]'  => ['[[:print:]@]{2}', '/[[:print:]@]{2}/', ' @'],
            '[:punct:]'  => ['[[:punct:]@]{2}', '/[[:punct:]@]{2}/', '.@'],
            '[:space:]'  => ['[[:space:]@]{2}', '/[[:space:]@]{2}/', ' @'],
            '[:xdigit:]' => ['[[:xdigit:]@]{2}', '/[[:xdigit:]@]{2}/', 'a@'],
            '[:word:]@'  => ['[[:word:]@]{2}', '/[[:word:]@]{2}/', 'a@'],
            '[:word:]'   => ['[[:word:]]', '/[[:word:]]/', 'a'],

            '[:^alpha:]'  => ['[[:^alpha:]@]{2}', '/[[:^alpha:]@]{2}/', '1@'],
            '[:^alnum:]'  => ['[[:^alnum:]@]{2}', '/[[:^alnum:]@]{2}/', ' @'],
            '[:^ascii:]'  => ['[[:^ascii:]@]{2}', '/[[:^ascii:]@]{2}/', chr(128) . '@'],
            '[:^blank:]'  => ['[[:^blank:]@]{2}', '/[[:^blank:]@]{2}/', 'a@'],
            '[:^cntrl:]'  => ['[[:^cntrl:]@]{2}', '/[[:^cntrl:]@]{2}/', "a@"],
            '[:^digit:]'  => ['[[:^digit:]@]{2}', '/[[:^digit:]@]{2}/', 'a@'],
            '[:^graph:]'  => ['[[:^graph:]@]{2}', '/[[:^graph:]@]{2}/', ' @'],
            '[:^lower:]'  => ['[[:^lower:]@]{2}', '/[[:^lower:]@]{2}/', 'A@'],
            '[:^upper:]'  => ['[[:^upper:]@]{2}', '/[[:^upper:]@]{2}/', 'a@'],
            '[:^print:]'  => ['[[:^print:]@]{2}', '/[[:^print:]@]{2}/', chr(127) . "@"],
            '[:^punct:]'  => ['[[:^punct:]@]{2}', '/[[:^punct:]@]{2}/', 'a@'],
            '[:^space:]'  => ['[[:^space:]@]{2}', '/[[:^space:]@]{2}/', 'a@'],
            '[:^xdigit:]' => ['[[:^xdigit:]@]{2}', '/[[:^xdigit:]@]{2}/', '-@'],
            '[:^word:]@'  => ['[[:^word:]@]{2}', '/[[:^word:]@]{2}/', '-@'],
            '[:^word:]'   => ['[[:^word:]]', '/[[:^word:]]/', '-'],
        ];
    }

    /**
     * @test
     */
    public function shouldIncludePlaceholderAfterCharacterClassPosix()
    {
        // when
        $pattern = Pattern::inject('^[[:alpha:]]@$', ['Placeholder']);
        // then
        $this->assertTrue($pattern->test('VPlaceholder'));
    }

    /**
     * @test
     */
    public function shouldIncludePlaceholderAfterCharacterClassShort()
    {
        // when
        $pattern = Pattern::inject('^[[:word:]]@$', ['Placeholder']);
        // then
        $this->assertTrue($pattern->test('VPlaceholder'));
    }

    /**
     * @test
     */
    public function shouldNotAcceptPlaceholderAfterInvalidPosixClass()
    {
        // when
        $pattern = Pattern::inject('^[[:x:]@]$', ['Placeholder']);
        // then
        $this->expectException(PregMalformedPatternException::class);
        $this->expectExceptionMessage('Unknown POSIX class name at offset 4');
        // then
        $pattern->test('Bar');
    }
}
