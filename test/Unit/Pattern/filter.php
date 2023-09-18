<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class filter extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('i{1}s{1}');
        $this->assertSame(
            ['stark' => 'Winter is coming', 'baratheon' => 'Ours is the Fury'],
            $pattern->filter([
                'greyjoy'   => 'We do not sow',
                'stark'     => 'Winter is coming',
                'martell'   => 'Unbowed, Unbent, Unbroken',
                'baratheon' => 'Ours is the Fury',
            ]));
    }
}
