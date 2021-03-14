<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\Detail;

use PHPUnit\Framework\TestCase;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use Test\Utils\Internal;
use Test\Utils\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all()
    {
        // given
        $matchPattern = $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', Functions::equals('matching'));

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->all();
        });

        // then
        $this->assertSame(['Nice', 'matching', 'Pattern'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all()
    {
        // given
        $matchPattern = $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', Functions::equals('matching'));

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->group(1)->all();
        });

        // then
        $this->assertSame(['N', null, 'P'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all_two()
    {
        // given
        $matchPattern = $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', Functions::notEquals('Nice'));

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->all();
        });

        // then
        $this->assertSame(['Nice', 'matching', 'Pattern'], $all);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all_two()
    {
        // given
        $matchPattern = $this->matchPattern('([A-Z])?[a-z]+', 'Nice matching Pattern', Functions::notEquals('Nice'));

        // when
        $all = $matchPattern->first(function (Detail $detail) {
            return $detail->group(1)->all();
        });

        // then
        $this->assertSame(['N', null, 'P'], $all);
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Internal::pattern($pattern), $subject, new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase());
    }
}
