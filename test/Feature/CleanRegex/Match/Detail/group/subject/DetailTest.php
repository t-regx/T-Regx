<?php
namespace Test\Feature\CleanRegex\Match\Detail\group\subject;

use PHPUnit\Framework\TestCase;

class DetailTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetSubject()
    {
        // given
        $detail = pattern('(?<loved>Boromir)(Faramir)?')->match('I love you, Boromir')->first();
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
        $detail = pattern('Boromir(?<well>Faramir)?')->match('I love you, Boromir')->first();
        // when
        $subject = $detail->group('well')->subject();
        // then
        $this->assertSame('I love you, Boromir', $subject);
    }
}
