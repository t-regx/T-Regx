<?php
namespace Test\Feature\CleanRegex\match\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Matcher;
use TRegx\Exception\MalformedPatternException;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Matcher
 */
class MatcherTest extends TestCase
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

    private function assertTests(Matcher $matcher): void
    {
        $this->assertTrue($matcher->test());
        $this->assertFalse($matcher->fails());
    }

    private function assertFails(Matcher $matcher): void
    {
        $this->assertFalse($matcher->test());
        $this->assertTrue($matcher->fails());
    }

    /**
     * @test
     */
    public function shouldTestThrowForMalformedPattern()
    {
        // given
        $matcher = pattern('+')->match('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->test();
    }

    /**
     * @test
     */
    public function shouldFailsThrowForMalformedPattern()
    {
        // given
        $matcher = pattern('+')->match('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $matcher->fails();
    }
}
