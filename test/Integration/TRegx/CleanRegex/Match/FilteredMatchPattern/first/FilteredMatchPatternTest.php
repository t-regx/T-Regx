<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\first;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGetFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals($first, 'nice');
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $all = $matchPattern->first(function (Match $match) {
            return $match->all();
        });

        // then
        $this->assertEquals($all, ['nice', 'pattern']);
    }

    /**
     * @test
     */
    public function shouldGetFirst_callMatch_group_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $all = $matchPattern->first(function (Match $match) {
            return $match->group(0)->all();
        });

        // then
        $this->assertEquals($all, ['nice', 'pattern']);
    }

    /**
     * @test
     */
    public function shouldGetFirst_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals($first, 'matching');
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_notMatched()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notMatches();

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldNotGetFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // then
        $this->expectException(SubjectNotMatchedException::class);
        $this->expectExceptionMessage('Expected to get first match, but subject was not matched');

        // when
        $matchPattern->first();
    }

    /**
     * @test
     */
    public function shouldNotInvokeFilter()
    {
        // given
        $invoked = [];
        $matchPattern = $this->matchPattern('\w+', 'One, two, three, four, five', function (Match $match) use (&$invoked) {
            $invoked[] = $match->text();
            return true;
        });

        // when
        $matchPattern->first();

        // then
        $this->assertEquals(['One'], $invoked);
    }

    private function standardMatchPattern_notFirst(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() > 0;
        });
    }

    private function standardMatchPattern(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_notMatches(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'NOT MATCHING', function () {
            return true;
        });
    }

    private function standardMatchPattern_filtered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', function () {
            return false;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
