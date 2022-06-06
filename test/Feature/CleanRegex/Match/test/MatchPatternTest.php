<?php
namespace Test\Feature\CleanRegex\Match\test;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Match\MatchPattern;
use function pattern;

class MatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldTestSubject()
    {
        // given
        $subject = 'You forgot one very important thing, mate';
        // when
        $matchPattern = pattern($subject)->match($subject);
        // then
        $this->assertTests(true, $matchPattern);
    }

    /**
     * @test
     */
    public function shouldFailSubject()
    {
        // given
        $subject = 'You forgot one very important thing, mate';
        // when
        $matchPattern = pattern($subject)->match("I'm captain Jack Sparrow");
        // then
        $this->assertTests(false, $matchPattern);
    }

    /**
     * @test
     */
    public function shouldTestSubjectRegularExpression()
    {
        // when
        $matchPattern = pattern('Foo(Bar)')->match('FooBar');
        // then
        $this->assertTests(true, $matchPattern);
    }

    private function assertTests(bool $expected, MatchPattern $match): void
    {
        $this->assertSame($expected, $match->test());
        $this->assertSame(!$expected, $match->fails());
    }
}
