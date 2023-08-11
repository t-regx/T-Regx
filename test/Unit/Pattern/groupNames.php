<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class groupNames extends TestCase
{
    public function test()
    {
        $pattern = new Pattern("(?<Drogon>), (), (?P<Rhaegal>) (and) (?'Viserion')");
        $this->assertSame(['Drogon', null, 'Rhaegal', null, 'Viserion'], $pattern->groupNames());
    }

    /**
     * @test
     */
    public function empty()
    {
        $pattern = new Pattern('pattern');
        $this->assertEmpty($pattern->groupNames());
    }

    /**
     * @test
     */
    public function indexed()
    {
        $pattern = new Pattern('(group) (second group)');
        $this->assertSame([null, null], $pattern->groupNames());
    }

    /**
     * @test
     */
    public function named()
    {
        $pattern = new Pattern("(?<Drogon>),(?P<Rhaegal>) and (?'Viserion')");
        $this->assertSame(['Drogon', 'Rhaegal', 'Viserion'], $pattern->groupNames());
    }
}
