<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\DelimiterException;
use Regex\Pattern;
use TRegx\PhpUnit\DataProviders\DataProvider;
use function Test\Fixture\Functions\catching;

class _delimiter extends TestCase
{
    public function test()
    {
        $string = (string)new Pattern('[a-z]+');
        $this->assertSame('/[a-z]+/DX', $string);
    }

    /**
     * @test
     * @dataProvider strings
     */
    public function delimiters(string $input, string $delimited)
    {
        $this->assertSame($delimited . 'DX', (string)new Pattern($input));
    }

    /**
     * @test
     * @dataProvider strings
     */
    public function match(string $input)
    {
        $pattern = new Pattern("(\Q$input\E)");
        $this->assertSame([$input], $pattern->search($input));
    }

    public function strings(): DataProvider
    {
        return DataProvider::tuples(
            ['tcp/ip', '#tcp/ip#'],
            ['query#anchor', '/query#anchor/'],
            ['/path#anchor', '%/path#anchor%'],
            ['/path#anchor%20', '~/path#anchor%20~'],
            ['/path#anchor%20~temp', '+/path#anchor%20~temp+'],
            ['/path#anchor+%20~temp', '!/path#anchor+%20~temp!'],
            ['/path#anchor+%20~!', '@/path#anchor+%20~!@'],
            ['user@/path#+%20~!', '_user@/path#+%20~!_'],
            ['_user@/path#+%20~!', ';_user@/path#+%20~!;'],
            ['_user@/path#+%20~!;', '`_user@/path#+%20~!;`'],
            ['_user@/`path`#+%20~!;', '-_user@/`path`#+%20~!;-'],
            ['_user@/`path`#+%20~-!;', '=_user@/`path`#+%20~-!;='],
            ['_user@/`path`#+%20~-!;q=a', ',_user@/`path`#+%20~-!;q=a,'],
            ['_user@/`path`#+%20~-!,;q=a', "\1_user@/`path`#+%20~-!,;q=a\1"],
            ["_user@/`path`#+%20~-!,;q=\1", "\2_user@/`path`#+%20~-!,;q=\1\2"],
        );
    }

    /**
     * @test
     */
    public function undelimitable()
    {
        catching(fn() => new Pattern("_user@/`path`#+%20~-!,;q=\1\2"))
            ->assertException(DelimiterException::class)
            ->assertMessage('Failed to delimiter the given regular expression.');
    }
}
