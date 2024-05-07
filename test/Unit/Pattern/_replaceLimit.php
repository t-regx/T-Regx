<?php
namespace Test\Unit\Pattern;

use PHPUnit\Framework\TestCase;
use Regex\Pattern;

class _replaceLimit extends TestCase
{
    /**
     * @test
     */
    public function replace()
    {
        $word = new Pattern('\w+');
        $this->assertSame(
            'replaced replaced the Fury',
            $word->replace('Ours is the Fury', 'replaced', 2));
    }

    /**
     * @test
     */
    public function replaceGroup()
    {
        $word = new Pattern('(\w+)()');
        $this->assertSame(
            '  the Fury',
            $word->replaceGroup('Ours is the Fury', 2, 2));
    }

    /**
     * @test
     */
    public function replaceCount()
    {
        $word = new Pattern('\w+');
        $this->assertSame(
            ['replaced replaced the Fury', 2],
            $word->replaceCount('Ours is the Fury', 'replaced', 2));
    }

    /**
     * @test
     */
    public function replaceCallback()
    {
        $word = new Pattern('(\w+)');
        $this->assertSame(
            'replaced replaced the Fury',
            $word->replaceCallback('Ours is the Fury', fn() => 'replaced', 2));
    }
}
