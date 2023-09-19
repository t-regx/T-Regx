<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class subject extends TestCase
{
    private string $subject = 'Fear cuts deeper than swords';

    /**
     * @test
     */
    public function first()
    {
        $pattern = new Pattern('fear', 'i');
        $match = $pattern->first($this->subject);
        $this->assertSame($this->subject, $match->subject());
    }

    /**
     * @test
     */
    public function match()
    {
        $pattern = new Pattern('fear', 'i');
        $match = $pattern->match($this->subject)->first();
        $this->assertSame($this->subject, $match->subject());
    }
}
