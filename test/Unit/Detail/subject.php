<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class subject extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('fear', 'i');
        $subject = 'Fear cuts deeper than swords';
        $match = $pattern->first($subject);
        $this->assertSame($subject, $match->subject());
    }
}
