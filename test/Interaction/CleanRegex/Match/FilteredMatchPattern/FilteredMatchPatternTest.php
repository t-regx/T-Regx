<?php
namespace Test\Interaction\TRegx\CleanRegex\Match\FilteredMatchPattern;

use PHPUnit\Framework\TestCase;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\AbstractMatchPattern;
use TRegx\CleanRegex\Match\Details\Detail;
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
        $this->assertEmpty($only);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Detail $detail) {
            return str_split($detail, 3);
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
        $mapper = function (Detail $detail) {
            return lcfirst(strtoupper($detail));
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertEquals(['fIRST', 'tHIRD', 'fOURTH'], $mapped);
    }

    /**
     * @test
     */
    public function shouldFindFirst_firstFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_firstFiltered();
        $callback = function (Detail $detail) {
            return 'for first: ' . $detail->text();
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: second', $findFirst);
    }

    /**
     * @test
     */
    public function shouldForFirst_secondFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_secondFiltered();
        $callback = function (Detail $detail) {
            return 'for first: ' . $detail->text();
        };

        // when
        $findFirst = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: first', $findFirst);
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
            ->matchPattern($pattern, $subject, function (Detail $detail) {
                return $detail->text() != 'forgot';
            })
            ->filter(function (Detail $detail) {
                return $detail->text() != 'very';
            })
            ->filter(function (Detail $detail) {
                return $detail->text() != 'mate';
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
        $pattern = $this->matchPattern('\w+', 'One two three four five six', function (Detail $detail) use (&$invokedFor) {
            $invokedFor[] = $detail->text();
            return $detail->text() === 'four';
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
        $pattern = $this->matchPattern('\w+', 'One two three four five six', function (Detail $detail) use (&$invokedFor) {
            $invokedFor[] = $detail->text();
            return $detail->text() === 'four';
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
        $callback = function (Detail $detail) use (&$matches) {
            $matches[] = $detail->text();
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
        $iterator = $matchPattern->getIterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertEquals(['first', 'third', 'fourth'], $array);
    }

    private function standardMatchPattern_secondFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', function (Detail $detail) {
            return $detail->index() != 1;
        });
    }

    private function standardMatchPattern_firstFiltered(): AbstractMatchPattern
    {
        return $this->matchPattern('[a-z]+', 'first second third fourth', function (Detail $detail) {
            return $detail->index() > 0;
        });
    }

    private function standardMatchPattern_group(string $subject, string $filteredOut): AbstractMatchPattern
    {
        return $this->matchPattern('(?<capital>[A-Z])[a-z]+', $subject, function (Detail $detail) use ($filteredOut) {
            return $detail->text() !== $filteredOut;
        });
    }

    private function matchPattern(string $pattern, string $subject, callable $predicate): AbstractMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(InternalPattern::standard($pattern), $subject, new UserData()), new Predicate($predicate)));
    }
}
