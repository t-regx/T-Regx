<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class offset extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.', 'u');
        $detail = $pattern->first('Óurs is the Füry!');
        $this->assertSame(12, $detail->offset());
    }
}
