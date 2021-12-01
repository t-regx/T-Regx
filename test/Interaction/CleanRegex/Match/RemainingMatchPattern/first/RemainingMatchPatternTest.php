<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\first;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use Test\Fakes\CleanRegex\Internal\Match\CallbackPredicate;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern::first
 */
class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_group_asInt_first()
    {
        // given
        $matchPattern = $this->matchPattern('(\d+)', '14 15', DetailFunctions::notEquals('14'));

        // when
        $result = $matchPattern->group(1)->asInt()->first();

        // then
        $this->assertSame($result, 15);
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): RemainingMatchPattern
    {
        $base = new ApiBase(Definitions::pattern($pattern), new StringSubject($subject), new UserData());
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                $base,
                new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new LazyMatchAllFactory($base));
    }
}
