<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern;

use PHPUnit\Framework\TestCase;
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
    public function shouldAll()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $all = $matchPattern->all();

        // then
        $this->assertEquals(['first', 'third', 'fourth'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertEquals(['first', 'third'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertEquals(['first'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_0()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

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
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Match $match) {
            return str_split($match, 3);
        };

        // when
        $flatMap = $matchPattern->flatMap($callback);

        // then
        $this->assertEquals(['sec', 'ond', 'thi', 'rd', 'fou', 'rth'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals('second', $first);
    }

    /**
     * @test
     */
    public function shouldFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals('first', $first);
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
        $this->assertEquals([0, 13, 19], $offsets);
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
        $this->assertEquals(['F', 'T', 'F'], $all);
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
        $this->assertEquals('S', $firstGroup);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $mapper = function (Match $match) {
            return lcfirst(strtoupper($match));
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertEquals(['fIRST', 'tHIRD', 'fOURTH'], $mapped);
    }

    /**
     * @test
     */
    public function shouldForFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Match $match) {
            return 'for first: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: second', $forFirst);
    }

    /**
     * @test
     */
    public function shouldForFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $callback = function (Match $match) {
            return 'for first: ' . $match->text();
        };

        // when
        $forFirst = $matchPattern->forFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: first', $forFirst);
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
        $all = $pattern->all();

        // then
        $this->assertEquals(['four'], $all);
        $this->assertEquals(['One', 'two', 'three', 'four', 'five', 'six'], $invokedFor);
    }

    /**
     * @test
     */
    public function shouldForEach()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $matches = [];
        $callback = function (Match $match) use (&$matches) {
            $matches[] = $match->text();
        };

        // when
        $matchPattern->forEach($callback);

        // then
        $this->assertEquals(['first', 'third', 'fourth'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();

        // when
        $iterator = $matchPattern->iterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertEquals(['first', 'third', 'fourth'], $array);
    }

    private function standardMatchPattern_secondFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', function (Match $match) {
            return $match->index() != 1;
        });
    }

    private function standardMatchPattern_firstFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', function (Match $match) {
            return $match->index() > 0;
        });
    }

    private function standardMatchPattern_group(string $subject, string $filteredOut): AbstractMatchPattern
    {
        return $this->matchPattern('(?<capital>[A-Z])[a-z]+', $subject, function (Match $match) use ($filteredOut) {
            return $match->text() !== $filteredOut;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(new InternalPattern($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
