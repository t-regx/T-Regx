<?php
namespace Test\Feature\CleanRegex\_prepared\_syntax;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class Test extends TestCase
{
    /**
     * @test
     */
    public function shouldResetCapturedString()
    {
        // given
        $pattern = Pattern::of('foo\Kbar');
        // when
        $match = $pattern->search('foobar')->first();
        // then
        $this->assertSame('bar', $match);
    }
}
