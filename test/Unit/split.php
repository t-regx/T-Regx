<?php
namespace Test\Unit;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class split extends TestCase
{
    public function test()
    {
        $pattern = new Pattern(', ?');
        $pieces = $pattern->split('Father, Mother, Maiden, Crone, Warrior, Smith, Stranger');
        $this->assertSame(['Father', 'Mother', 'Maiden', 'Crone', 'Warrior', 'Smith', 'Stranger'], $pieces);
    }

    /**
     * @test
     */
    public function separator()
    {
        $pattern = new Pattern(' (is) ');
        $this->assertSame(
            ['Knowledge', 'is', 'Power'],
            $pattern->split('Knowledge is Power'));
    }

    /**
     * @test
     */
    public function separatorMany()
    {
        $pattern = new Pattern('(,)( )');
        $this->assertSame(
            ['Ghost', ',', ' ', 'Nymeria', ',', ' ', 'Lady', ',', ' ', 'Summer'],
            $pattern->split('Ghost, Nymeria, Lady, Summer'));
    }

    /**
     * @test
     */
    public function separatorEmpty()
    {
        $pattern = new Pattern('-()');
        $pieces = $pattern->split('Oath-keeper');
        $this->assertSame(['Oath', '', 'keeper'], $pieces);
    }

    /**
     * @test
     */
    public function unmatchedGroup()
    {
        $pattern = new Pattern(' (not)?(is) ');
        $this->assertSame(
            ["Knowledge", null, "is", "Power."],
            $pattern->split("Knowledge is Power."));
    }
}
