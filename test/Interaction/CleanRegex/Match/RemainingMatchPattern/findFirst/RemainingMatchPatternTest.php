<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern\findFirst;

use PHPUnit\Framework\TestCase;
use Test\Utils\AssertsOptional;
use Test\Utils\Functions;
use Test\Utils\Impl\CallbackPredicate;
use Test\Utils\Impl\ThrowApiBase;
use Test\Utils\Internal;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

class RemainingMatchPatternTest extends TestCase
{
    use AssertsOptional;

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
        $this->assertOptionalHas('value: Foo', $findFirst);
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
        $this->assertOptionalHas('value: Bar', $findFirst);
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
        $this->assertOptionalEmpty($findFirst);
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
        $this->assertOptionalEmpty($findFirst);
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
