<?php
namespace Test\Feature\CleanRegex\match\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\Search;
use TRegx\CleanRegex\Pattern;
use TRegx\Exception\MalformedPatternException;

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
        $search = Pattern::of($subject)->search($subject);
        // then
        $this->assertTests($search);
    }

    /**
     * @test
     */
    public function shouldFailSubject()
    {
        // given
        $pattern = Pattern::of('You forgot one very important thing, mate');
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
        $search = Pattern::of('Valar (Morghulis)')->search('Valar Morghulis');
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
        $search = Pattern::of('+')->search('Valar Dohaeris');
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
        $search = Pattern::of('+')->search('Valar Dohaeris');
        // then
        $this->expectException(MalformedPatternException::class);
        $this->expectExceptionMessage('Quantifier does not follow a repeatable item at offset 0');
        // when
        $search->fails();
    }
}
