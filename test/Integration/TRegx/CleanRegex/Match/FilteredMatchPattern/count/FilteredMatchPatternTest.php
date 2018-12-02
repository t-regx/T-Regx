<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\count;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(2, $count);
    }

    /**
     * @test
     */
    public function shouldCount_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_all();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(3, $count);
    }

    /**
     * @test
     */
    public function shouldCount_notMatching()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notMatches();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(0, $count);
    }

    /**
     * @test
     */
    public function shouldCount_filtered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(0, $count);
    }

    private function standardMatchPattern(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_notMatches(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'NOT MATCHING', function () {
            return true;
        });
    }

    private function standardMatchPattern_all(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function () {
            return true;
        });
    }

    private function standardMatchPattern_filtered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', function (Match $match) {
            return false;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
