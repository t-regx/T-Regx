<?php
namespace Test\Feature\TRegx\CleanRegex;

use PHPUnit\Framework\TestCase;

class PatternTest extends TestCase
{
    /**
     * @test
     */
    public function should_matches()
    {
        // when
        $matches = pattern('\d')->matches('abc');

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function should_fails()
    {
        // when
        $matches = pattern('\d')->fails('abc');

        // then
        $this->assertTrue($matches);
    }
}
