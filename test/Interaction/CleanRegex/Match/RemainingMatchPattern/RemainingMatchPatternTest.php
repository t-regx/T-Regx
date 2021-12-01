<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\RemainingMatchPattern;

use PHPUnit\Framework\TestCase;
use Test\Fakes\CleanRegex\Internal\Match\Base\ThrowApiBase;
use Test\Fakes\CleanRegex\Internal\Match\CallbackPredicate;
use Test\Utils\AssertsSameMatches;
use Test\Utils\Definitions;
use Test\Utils\DetailFunctions;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\DetailPredicateBaseDecorator;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\RemainingMatchPattern;

/**
 * @covers \TRegx\CleanRegex\Match\RemainingMatchPattern
 */
class RemainingMatchPatternTest extends TestCase
{
    use AssertsSameMatches;

    /**
     * @test
     */
    public function shouldGet_getIterator()
    {
        // given
        $matchPattern = $this->matchPattern('\w+', 'a b c b e', DetailFunctions::notEquals('b'));

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $this->assertSameMatches(['a', 'c', 'e'], iterator_to_array($iterator));
    }

    /**
     * @test
     */
    public function shouldOffsets()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertSame([0, 13, 19], $offsets);
    }

    /**
     * @test
     */
    public function shouldGroup_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third Fourth', 'Second');

        // when
        $all = $matchPattern->group('capital')->all();

        // then
        $this->assertSame(['F', 'T', 'F'], $all);
    }

    /**
     * @test
     */
    public function shouldGroup_only()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third Fourth', 'Second');

        // when
        $all = $matchPattern->group('capital')->only(2);

        // then
        $this->assertSame(['F', 'T'], $all);
    }

    /**
     * @test
     */
    public function shouldGroup_first()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group('First Second Third', 'First');

        // when
        $firstGroup = $matchPattern->group('capital')->first();

        // then
        $this->assertSame('S', $firstGroup);
    }

    /**
     * @test
     */
    public function shouldFindFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Detail $detail) {
            return "for first: $detail";
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertSame('for first: second', $findFirst);
    }

    /**
     * @test
     */
    public function shouldForFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $callback = function (Detail $detail) {
            return "for first: $detail";
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertSame('for first: first', $findFirst);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertSame(['first', 'third', 'fourth'], $array);
    }

    private function standardMatchPattern_secondFiltered(): RemainingMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', DetailFunctions::notEquals('second'));
    }

    private function standardMatchPattern_firstFiltered(): RemainingMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', DetailFunctions::notEquals('first'));
    }

    private function standardMatchPattern_group(string $subject, string $filteredOut): RemainingMatchPattern
    {
        return $this->matchPattern('(?<capital>[A-Z])[a-z]+', $subject, DetailFunctions::notEquals($filteredOut));
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
