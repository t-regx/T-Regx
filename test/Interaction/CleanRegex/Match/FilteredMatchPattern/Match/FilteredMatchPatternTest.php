<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\FilteredMatchPattern\Match;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_one();

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->all();
        });

        // then
        $this->assertSame(['matching'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_one();

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->group(1)->all();
        });

        // then
        $this->assertSame([null], $all);
    }

    private function standardMatchPattern_one(): AbstractMatchPattern
    {
        return $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', function (Detail $detail) {
            return $detail->index() == 1;
        });
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all_two()
    {
        // given
        $matchPattern = $this->standardMatchPattern_two();

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->all();
        });

        // then
        $this->assertSame(['matching', 'Pattern'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all_two()
    {
        // given
        $matchPattern = $this->standardMatchPattern_two();

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->group(1)->all();
        });

        // then
        $this->assertSame([null, 'P'], $all);
    }

    private function standardMatchPattern_two(): AbstractMatchPattern
    {
        return $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', function (Detail $detail) {
            return $detail->index() > 0;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(InternalPattern::standard($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
