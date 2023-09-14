<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class test extends TestCase
{
    /**
     * @test
     */
    public function test()
    {
        $pattern = new Pattern('Ed{2}ard Stark');
        $this->assertTrue($pattern->test('Eddard Stark'));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('Valar Morghulis');
        $this->assertFalse($pattern->test('Valar Dohaeris'));
    }

    /**
     * @test
     * @doesNotPerformAssertions
     */
    public function noSecondCall()
    {
        $pattern = new Pattern('(\d+\d+)+3');
        $pattern->test('123, 11111111111111111111 3');
    }
}
