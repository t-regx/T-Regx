<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class getIterator extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        [$match] = \iterator_to_array($pattern->match('Ours is the Fury'));
        $this->assertSame('Fury', $match->text());
    }
}
