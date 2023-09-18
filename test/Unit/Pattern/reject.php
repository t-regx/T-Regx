<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class reject extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('i{1}s{1}');
        $this->assertSame(
            ['greyjoy' => 'We do not sow', 'martell' => 'Unbowed, Unbent, Unbroken'],
            $pattern->reject([
                'greyjoy'   => 'We do not sow',
                'stark'     => 'Winter is coming',
                'martell'   => 'Unbowed, Unbent, Unbroken',
                'baratheon' => 'Ours is the Fury',
            ]));
    }
}
