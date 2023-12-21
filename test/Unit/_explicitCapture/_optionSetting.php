<?php
namespace Test\Unit\_explicitCapture;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use Regex\SyntaxException;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _optionSetting extends TestCase
{
    /**
     * @dataProvider patterns
     */
    public function test(string $pattern, string $expected)
    {
        $this->assertDelimited($expected, new Pattern($pattern, 'n'));
    }

    public function patterns(): DataProvider
    {
        $verbs = DataProvider::list(
            'UTF', 'UCP',
            'NO_START_OPT', 'NO_DOTSTAR_ANCHOR', 'NO_AUTO_POSSESS',
            'NOTEMPTY', 'NOTEMPTY_ATSTART',
            'NO_JIT',
            'CR', 'LF', 'CRLF', 'ANYCRLF', 'ANY', 'NUL',
            'BSR_ANYCRLF', 'BSR_UNICODE',
            'LIMIT_DEPTH=123',
            'LIMIT_HEAP=234',
            'LIMIT_MATCH=456');
        return DataProvider::join(
            $verbs->map(fn(string $verb) => ["(*$verb)(.)", "/(*$verb)(?n)(.)/DX"]),
            DataProvider::of([
                'notation substring'          => ['.\Q(*UTF)', '/(?n).\Q(*UTF)/DX'],
                'notation substring, (*UTF)'  => ['(*UTF).\Q(*UTF)\E', '/(*UTF)(?n).\Q(*UTF)\E/DX'],
                'multiple, (*NOTEMPTY)(*UTF)' => ['(*NOTEMPTY)(*UTF)(.)', '/(*NOTEMPTY)(*UTF)(?n)(.)/DX']
            ])
        );
    }

    /**
     * @test
     */
    public function unknown()
    {
        catching(fn() => new Pattern('(*UTF)(*INVALID)', 'n'))
            ->assertException(SyntaxException::class)
            ->assertMessageStartsWith('(*VERB) not recognized or malformed');
    }

    /**
     * @test
     */
    public function modifiers()
    {
        $this->assertDelimited('/(?n)\w/ADXim', new Pattern('\w', 'Anim'));
    }

    /**
     * @test
     */
    public function commentsAndWhitespace()
    {
        $this->assertDelimited('/(?n)word/DXx', new Pattern('word', 'xn'));
    }

    private function assertDelimited(string $expected, Pattern $pattern)
    {
        $this->assertSame($expected, $pattern->delimited());
    }
}
