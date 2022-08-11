<?php
namespace Test\Feature\CleanRegex\Match\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Search;
use TRegx\Exception\MalformedPatternException;
use function pattern;

/**
 * @covers \TRegx\CleanRegex\Match\Search
 */
class SearchTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTestSubject()
    {
        // given
        $subject = "I'm dishonest, and a dishonest man you can always trust to be dishonest";
        // when
        $search = pattern($subject)->search($subject);
        // then
        $this->assertTests($search);
    }

    /**
     * @test
     */
    public function shouldFailSubject()
    {
        // given
        $pattern = pattern('You forgot one very important thing, mate');
        // when
        $search = $pattern->search("I'm captain Jack Sparrow");
        // then
        $this->assertFails($search);
    }

    /**
     * @test
     */
    public function shouldTestSubjectRegularExpression()
    {
        // when
        $search = pattern('Valar (Morghulis)')->search('Valar Morghulis');
        // then
        $this->assertTests($search);
    }

    private function assertTests(Search $search): void
    {
        $this->assertTrue($search->test());
        $this->assertFalse($search->fails());
    }

    private function assertFails(Search $search): void
    {
        $this->assertFalse($search->test());
        $this->assertTrue($search->fails());
    }

    /**
     * @test
     */
    public function shouldTestThrowForMalformedPattern()
    {
        // given
        $search = pattern('+')->search('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->test();
    }

    /**
     * @test
     */
    public function shouldFailsThrowForMalformedPattern()
    {
        // given
        $search = pattern('+')->search('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->fails();
    }
}
