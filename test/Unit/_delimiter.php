<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _delimiter extends TestCase
{
    public function test()
    {
        $string = (string)new Pattern('[a-z]+');
        $this->assertSame('/[a-z]+/DX', $string);
    }
}
