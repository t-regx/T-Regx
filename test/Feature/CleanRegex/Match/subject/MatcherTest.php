<?php
namespace Test\Feature\CleanRegex\Match\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $matcher = Pattern::of('Foo')->match('Not all those who wander are lost');
        // when
        $subject = $matcher->subject();
        // then
        $this->assertSame('Not all those who wander are lost', $subject);
    }
}
