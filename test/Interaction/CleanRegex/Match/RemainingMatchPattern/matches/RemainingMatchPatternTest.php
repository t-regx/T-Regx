<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\matches;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use Test\Fakes\CleanRegex\Internal\Match\CallbackPredicate;
use Test\Fakes\CleanRegex\Internal\Match\MatchAll\ThrowFactory;
use Test\Utils\Definitions;
use Test\Utils\Functions;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern::test
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern::fails
 */
class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::constant(true));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::notEquals('matching'));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertTrue($matches);
        $this->assertFalse($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_notMatched()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Bar', Functions::fail());

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern long', Functions::constant(false));

        // when
        $matches = $matchPattern->test();
        $fails = $matchPattern->fails();

        // then
        $this->assertFalse($matches);
        $this->assertTrue($fails);
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): RemainingMatchPattern
    {
        return new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Definitions::pattern($pattern), new StringSubject($subject), new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase(),
            new ThrowFactory());
    }
}
