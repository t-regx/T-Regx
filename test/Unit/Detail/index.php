<?php
namespace Test\Unit\Detail;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;
use function Test\Fixture\Functions\collect;
use function Test\Fixture\Functions\collectLast;

class index extends TestCase
{
    public function test()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->first('Ours is the Fury');
        $this->assertSame(0, $match->index());
    }

    /**
     * @test
     */
    public function matchFirst()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->match('Ours is the Fury')->first();
        $this->assertSame(0, $match->index());
    }

    /**
     * @test
     */
    public function matchFirstOrNull()
    {
        $pattern = new Pattern('F.r.');
        $match = $pattern->match('Ours is the Fury')->firstOrNull();
        $this->assertSame(0, $match->index());
    }

    /**
     * @test
     */
    public function replaceFirst()
    {
        $pattern = new Pattern('\w+');
        $pattern->replaceCallback('Hear Me Roar!', collect($match, ''));
        $this->assertSame(0, $match->index());
    }

    /**
     * @test
     */
    public function replaceLast()
    {
        $pattern = new Pattern('\w+');
        $pattern->replaceCallback('We Do Not Sow', collectLast($match, ''));
        $this->assertSame(3, $match->index());
    }
}
