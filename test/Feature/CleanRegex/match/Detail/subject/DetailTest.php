<?php
namespace Test\Feature\CleanRegex\match\Detail\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // when
        $detail = Pattern::of('.+')->match('Not all who wander are lost')->first();
        // when
        $subject = $detail->subject();
        // then
        $this->assertSame('Not all who wander are lost', $subject);
    }
}
