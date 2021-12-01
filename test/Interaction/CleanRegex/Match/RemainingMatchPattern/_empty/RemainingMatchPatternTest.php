<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\_empty;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use Test\Fakes\CleanRegex\Internal\Match\CallbackPredicate;
use Test\Fakes\CleanRegex\Internal\Match\MatchAll\ThrowFactory;
use Test\Utils\AssertsOptional;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern
 */
class RemainingMatchPatternTest extends TestCase
{
    use AssertsSameMatches, AssertsOptional;

    /**
     * @test
     */
    public function shouldGet_Offsets_all()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertSame([1, 4, 12, 15], $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_first()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $offset = $matchPattern->offsets()->first();

        // then
        $this->assertSame(1, $offset);
    }

    private function matchPattern(callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Definitions::pattern('(?<=\()[a-z]?(?=\))'), new StringSubject('() (a) (b) () (c)'), new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new ThrowFactory());
    }
}
