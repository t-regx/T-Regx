<?php
namespace Test\Feature\CleanRegex\match\Detail\group\subject;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Pattern;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = Pattern::of('(?<loved>Boromir)(Faramir)?')->match('I love you, Boromir')->first();
        // when
        $matchedSubject = $detail->group('loved')->subject();
        // then
        $this->assertSame('I love you, Boromir', $matchedSubject);
    }

    /**
     * @test
     */
    public function shouldGetSubject_forUnmatchedGroup()
    {
        // given
        $detail = Pattern::of('Boromir(?<well>Faramir)?')->match('I love you, Boromir')->first();
        // when
        $subject = $detail->group('well')->subject();
        // then
        $this->assertSame('I love you, Boromir', $subject);
    }
}
