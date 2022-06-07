<?php
namespace Test\Feature\CleanRegex\Match\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $match = Pattern::of('Foo')->match('Not all those who wander are lost');
        // when
        $subject = $match->subject();
        // then
        $this->assertSame('Not all those who wander are lost', $subject);
    }
}
