<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Detail;
use Regex\Pattern;

class text extends TestCase
{
    /**
     * @test
     */
    public function text()
    {
        $match = $this->match(new Pattern('F.r.'), 'Ours is the Fury');
        $this->assertSame('Fury', $match->text());
    }

    /**
     * @test
     */
    public function cast()
    {
        $match = $this->match(new Pattern('F.r.'), 'Ours is the Fury');
        $this->assertSame('Fury', "$match");
    }

    private function match(Pattern $pattern, string $subject): Detail
    {
        return $pattern->first($subject);
    }
}
