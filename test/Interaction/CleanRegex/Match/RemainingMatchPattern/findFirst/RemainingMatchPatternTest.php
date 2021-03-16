<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\CallbackPredicate;
use Test\Utils\Functions;
use Test\Utils\Internal;
use Test\Utils\ThrowApiBase;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FindFirst\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FindFirst\OptionalImpl;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

class RemainingMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Foo', Functions::indexNotEquals(1));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertSame('value: Foo', $findFirst->orThrow());
        $this->assertInstanceOf(OptionalImpl::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldFindFirst_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern('(Foo|Bar)', 'Foo Bar', Functions::indexNotEquals(0));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertSame('value: Bar', $findFirst->orThrow());
        $this->assertInstanceOf(OptionalImpl::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotFindFirst_notMatched()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Lorem ipsum', Functions::constant(true));

        // when
        $findFirst = $matchPattern->findFirst(Functions::fail());

        // then
        $this->assertInstanceOf(EmptyOptional::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotFindFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern('Foo', 'Lorem ipsum', Functions::constant(false));

        // when
        $findFirst = $matchPattern->findFirst(Functions::fail());

        // then
        $this->assertInstanceOf(EmptyOptional::class, $findFirst);
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
