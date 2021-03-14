<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\matches;

use PHPUnit\Framework\TestCase;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use Test\Utils\Internal;
use Test\Utils\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

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
                new ApiBase(Internal::pattern($pattern), $subject, new UserData()),
                new CallbackPredicate($predicate)),
            new ThrowApiBase());
    }
}
