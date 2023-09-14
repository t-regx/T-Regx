<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class count extends TestCase
{
    public function test()
    {
        $lowercaseWords = new Pattern('\b[a-z]+\b');
        $count = $lowercaseWords->count('Fear cuts deeper than swords');
        $this->assertSame(4, $count);
    }
}
