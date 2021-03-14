<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\count;

use PHPUnit\Framework\TestCase;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use Test\Utils\Internal;
use Test\Utils\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::notEquals('matching'));

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(2, $count);
    }

    /**
     * @test
     */
    public function shouldCount_all()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern', Functions::constant(true));

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(3, $count);
    }

    /**
     * @test
     */
    public function shouldCount_notMatching()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Bar', Functions::fail());

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(0, $count);
    }

    /**
     * @test
     */
    public function shouldCount_filtered()
    {
        // given
        $matchPattern = $this->matchPattern('[a-z]+', 'nice matching pattern long', Functions::constant(false));

        // when
        $count = $matchPattern->count();

        // then
        $this->assertSame(0, $count);
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
