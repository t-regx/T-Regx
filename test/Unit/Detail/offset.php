<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\Pattern;
use Regex\UnicodeException;
use function Test\Fixture\Functions\catching;

class offset extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.', 'u');
        $detail = $pattern->first('Óurs is the Füry!');
        $this->assertSame(12, $detail->offset());
    }

    /**
     * @test
     */
    public function malformedUnicode()
    {
        $detail = $this->detail('.w', '€w');
        catching(fn() => $detail->offset())
            ->assertException(UnicodeException::class)
            ->assertMessage('Byte offset 2 does not point to a valid unicode code point.');
    }

    private function detail(string $pattern, string $subject, string $modifiers = ''): Detail
    {
        $unicode = new Pattern($pattern, $modifiers);
        return $unicode->first($subject);
    }
}
