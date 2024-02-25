<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class replaceCount extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('do|not');
        $output = $pattern->replaceCount('We do not sow', '.');
        $this->assertSame(['We . . sow', 2], $output);
    }
}
