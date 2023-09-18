<?php
namespace Test\Unit\Matcher;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class test extends TestCase
{
    public function test()
    {
        $words = new Pattern('\w+');
        $matcher = $words->match('Power resides where men believe it resides');
        $this->assertTrue($matcher->test());
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('Knowledge is power');
        $matcher = $pattern->match('Power is power');
        $this->assertFalse($matcher->test());
    }
}
