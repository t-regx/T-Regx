<?php
namespace Test\Feature\CleanRegex\Match\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $search = Pattern::of('Foo')->search('Not all those who wander are lost');
        // when
        $subject = $search->subject();
        // then
        $this->assertSame('Not all those who wander are lost', $subject);
    }
}
