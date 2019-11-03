<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\matches;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_allMatch();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotMatch_notMatched()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notMatches();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertFalse($matches);
    }

    private function standardMatchPattern(): FilteredMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_allMatch(): FilteredMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function () {
            return true;
        });
    }

    private function standardMatchPattern_notMatches(): FilteredMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'NOT MATCHING', function () {
            return true;
        });
    }

    private function standardMatchPattern_filtered(): FilteredMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', function () {
            return false;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): FilteredMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(InternalPattern::standard($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
