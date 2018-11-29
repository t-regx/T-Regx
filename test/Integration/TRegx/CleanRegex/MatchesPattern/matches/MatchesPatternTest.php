<?php
namespace Test\Integration\TRegx\CleanRegex\MatchesPattern\matches;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\MatchesPattern;

class MatchesPatternTest extends TestCase
{
    /**
     * @test
     */
    public function should_match()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/[a-z]/'), new SubjectableImpl('matching'));

        // when
        $result = $pattern->matches();

        // then
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function should_not_match()
    {
        // given
        $pattern = new MatchesPattern(new InternalPattern('/^[a-z]+$/'), new SubjectableImpl('not matching'));

        // when
        $result = $pattern->matches();

        // then
        $this->assertFalse($result);
    }
}
