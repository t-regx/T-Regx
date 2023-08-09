<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class search extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('[RB][a-z]+');
        $this->assertSame(
            ['Robert', 'Baratheon'],
            $pattern->search('King Robert I Baratheon was the seventeenth ruler of the Seven Kingdoms and the first king of the dynasty.'));
    }

    /**
     * @test
     */
    public function unmatched()
    {
        $pattern = new Pattern('Valar morghulis');
        $this->assertEmpty($pattern->search('Dohaeris'));
    }
}
