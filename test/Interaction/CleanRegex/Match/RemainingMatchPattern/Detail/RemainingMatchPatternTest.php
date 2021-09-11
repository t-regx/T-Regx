<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\Detail;

use PHPUnit\Framework\TestCase;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use Test\Utils\Impl\CallbackPredicate;
use Test\Utils\Impl\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern::first
 */
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
        $base = new ApiBase(Definitions::pattern($pattern), new StringSubject($subject), new UserData());
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator($base, new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new LazyMatchAllFactory($base));
    }
}
