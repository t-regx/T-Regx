<?php
namespace Test\Unit\TRegx\CleanRegex\Match\FilteredMatchPattern;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldAll()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $all = $matchPattern->all();

        // then
        $this->assertEquals(['nice', 'pattern'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->standardMatchPattern_long();

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertEquals(['nice', 'pattern'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->standardMatchPattern_long();

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertEquals(['nice'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_0()
    {
        // given
        $matchPattern = $this->standardMatchPattern_long();

        // when
        $only = $matchPattern->only(0);

        // then
        $this->assertEquals([], $only);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();
        $callback = function (Match $match) {
            return str_split($match, 3);
        };

        // when
        $flatMap = $matchPattern->flatMap($callback);

        // then
        $this->assertEquals(['mat', 'chi', 'ng', 'pat', 'ter', 'n'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals('matching', $first);
    }

    /**
     * @test
     */
    public function shouldOffsets()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertEquals([0, 14], $offsets);
    }

    /**
     * @test
     */
    public function shouldGroup_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group();

        // when
        $all = $matchPattern->group('capital')->all();

        // then
        $this->assertEquals(['M', 'P'], $all);
    }

    /**
     * @test
     */
    public function shouldGroup_first()
    {
        // given
        $matchPattern = $this->standardMatchPattern_group();

        // when
        $firstGroup = $matchPattern->group('capital')->first();

        // then
        $this->assertEquals('M', $firstGroup);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $mapper = function (Match $match) {
            return lcfirst(strtoupper($match));
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertEquals(['nICE', 'pATTERN'], $mapped);
    }

    /**
     * @test
     */
    public function shouldForFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'for first: ' . $match->text();
        };

        // when
        $first = $matchPattern->forFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: nice', $first);
    }

    /**
     * @test
     */
    public function shouldChain_filter()
    {
        // given
        $pattern = '\w+';
        $subject = '...you forgot one very important thing mate.';

        // when
        $filtered = $this
            ->matchPattern($pattern, $subject, function (Match $match) {
                return $match->text() != 'forgot';
            })
            ->filter(function (Match $match) {
                return $match->text() != 'very';
            })
            ->filter(function (Match $match) {
                return $match->text() != 'mate';
            })
            ->all();

        // then
        $this->assertEquals(['you', 'one', 'important', 'thing'], $filtered);
    }

    /**
     * @test
     */
    public function shouldFilter_first_untilFound()
    {
        // given
        $invokedFor = [];
        $pattern = $this->matchPattern('\w+', 'One two three four five six', function (Match $match) use (&$invokedFor) {
            $invokedFor[] = $match->text();
            return $match->text() === 'four';
        });

        // when
        $first = $pattern->first();

        // then
        $this->assertEquals('four', $first);
        $this->assertEquals(['One', 'two', 'three', 'four'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldFilter_all_untilFound()
    {
        // given
        $invokedFor = [];
        $pattern = $this->matchPattern('\w+', 'One two three four five six', function (Match $match) use (&$invokedFor) {
            $invokedFor[] = $match->text();
            return $match->text() === 'four';
        });

        // when
        $first = $pattern->all();

        // then
        $this->assertEquals(['four'], $first);
        $this->assertEquals(['One', 'two', 'three', 'four', 'five', 'six'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $matches = [];
        $callback = function (Match $match) use (&$matches) {
            $matches[] = $match->text();
        };

        // when
        $matchPattern->forEach($callback);

        // then
        $this->assertEquals(['nice', 'pattern'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $iterator = $matchPattern->iterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertEquals(['nice', 'pattern'], $array);
    }

    private function standardMatchPattern(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_notFirst(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern', function (Match $match) {
            return $match->index() > 0;
        });
    }

    private function standardMatchPattern_long(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'nice matching pattern long', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_group(): AbstractMatchPattern
    {
        return $this->matchPattern('(?<capital>[A-Z])[a-z]+', 'Nice Matching Pattern', function (Match $match) {
            return $match->text() !== 'Nice';
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject), new Predicate($predicate)));
    }
}
