<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\forFirst;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertEquals('value: nice', $forFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $forFirst);
    }

    /**
     * @test
     */
    public function shouldGetFirst_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertEquals('value: matching', $forFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $forFirst);
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_notMatched()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notMatches();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertInstanceOf(NotMatchedOptional::class, $forFirst);
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // then
        $forFirst = $matchPattern->forFirst($callback);

        // then
        $this->assertInstanceOf(NotMatchedOptional::class, $forFirst);
    }

    private function standardMatchPattern_notFirst(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() > 0;
        });
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

    private function standardMatchPattern_filtered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', function () {
            return false;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
