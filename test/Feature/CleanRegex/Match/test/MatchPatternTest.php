<?php
namespace Test\Feature\CleanRegex\Match\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\Exception\MalformedPatternException;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 * @covers \TRegx\CleanRegex\Match\MatchPattern
 */
class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTestSubject()
    {
        // given
        $subject = "I'm dishonest, and a dishonest man you can always trust to be dishonest";
        // when
        $match = pattern($subject)->match($subject);
        // then
        $this->assertTests($match);
    }

    /**
     * @test
     */
    public function shouldFailSubject()
    {
        // given
        $pattern = pattern('You forgot one very important thing, mate');
        // when
        $match = $pattern->match("I'm captain Jack Sparrow");
        // then
        $this->assertFails($match);
    }

    /**
     * @test
     */
    public function shouldTestSubjectRegularExpression()
    {
        // when
        $match = pattern('Valar (Morghulis)')->match('Valar Morghulis');
        // then
        $this->assertTests($match);
    }

    private function assertTests(MatchPattern $match): void
    {
        $this->assertTrue($match->test());
        $this->assertFalse($match->fails());
    }

    private function assertFails(MatchPattern $match): void
    {
        $this->assertFalse($match->test());
        $this->assertTrue($match->fails());
    }

    /**
     * @test
     */
    public function shouldTestThrowForMalformedPattern()
    {
        // given
        $match = pattern('+')->match('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->test();
    }

    /**
     * @test
     */
    public function shouldFailsThrowForMalformedPattern()
    {
        // given
        $match = pattern('+')->match('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $match->fails();
    }
}
