<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _systemEncoding extends TestCase
{
    public function setUp(): void
    {
        \mb_internal_encoding('7bit');
    }

    public function tearDown(): void
    {
        \mb_internal_encoding('UTF-8');
    }

    /**
     * @test
     */
    public function detailOffset()
    {
        $pattern = new Pattern('F.r.', 'u');
        $detail = $pattern->first('Óurs is the Füry!');
        $this->assertSame(12, $detail->offset());
    }
}
