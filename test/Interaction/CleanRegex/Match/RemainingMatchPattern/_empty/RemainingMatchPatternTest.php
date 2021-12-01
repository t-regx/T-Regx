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
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
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
    public function shouldGet_All()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $all = $matchPattern->all();

        // then
        $this->assertSame(['', 'a', '', 'c'], $all);
    }

    /**
     * @test
     */
    public function shouldGet_First()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('', $first);
    }

    /**
     * @test
     */
    public function shouldGet_First_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::indexNotEquals(0));

        // when
        $first = $matchPattern->first();

        // then
        $this->assertSame('a', $first);
    }

    /**
     * @test
     */
    public function shouldNotGet_First_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(false));

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get the first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldGet_findFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertOptionalHas('value: ', $findFirst);
    }

    /**
     * @test
     */
    public function shouldGet_findFirst_notFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::indexNotEquals(0));

        // when
        $findFirst = $matchPattern->findFirst(function (Detail $detail) {
            return "value: $detail";
        });

        // then
        $this->assertOptionalHas('value: a', $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotGet_findFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->matchPattern(Functions::constant(false));

        // when
        $findFirst = $matchPattern->findFirst(Functions::fail());

        // then
        $this->assertOptionalEmpty($findFirst);
    }

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

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $matchPattern = $this->matchPattern(DetailFunctions::notEquals('b'));

        // when
        $first = $matchPattern
            ->findFirst(function (Detail $detail) {
                return "for first: $detail";
            })
            ->orReturn('');

        // then
        $this->assertSame('for first: ', $first);
    }

    /**
     * @test
     */
    public function shouldChain_remaining()
    {
        // given
        $subject = '...you forgot one very important thing mate.';
        $pattern = new RemainingMatchPattern(
            new DetailPredicateBaseDecorator(
                new ApiBase(Definitions::pcre('/[a-z]+/'), new StringSubject($subject), new UserData()),
                new CallbackPredicate(DetailFunctions::notEquals('forgot'))),
            new ThrowApiBase(),
            new ThrowFactory());

        // when
        $filtered = $pattern
            ->remaining(DetailFunctions::notEquals('very'))
            ->remaining(DetailFunctions::notEquals('mate'))
            ->all();

        // then
        $this->assertSame(['you', 'one', 'important', 'thing'], $filtered);
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
