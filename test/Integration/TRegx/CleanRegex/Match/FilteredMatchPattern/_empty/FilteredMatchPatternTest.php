<?php
namespace Test\Integration\TRegx\CleanRegex\Match\FilteredMatchPattern\_empty;

use PHPUnit\Framework\TestCase;
use Test\Utils\Functions;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\FilteredMatchPattern;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\NotMatchedOptional;

class FilteredMatchPatternTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGet_All()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $all = $matchPattern->all();

        // then
        $this->assertEquals(['', 'a', '', 'c'], $all);
    }

    /**
     * @test
     */
    public function shouldOnly_2()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $only = $matchPattern->only(2);

        // then
        $this->assertEquals(['', 'a'], $only);
    }

    /**
     * @test
     */
    public function shouldOnly_1()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $only = $matchPattern->only(1);

        // then
        $this->assertEquals([''], $only);
    }

    /**
     * @test
     */
    public function shouldCount()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(4, $count);
    }

    /**
     * @test
     */
    public function shouldCount_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_all();

        // when
        $count = $matchPattern->count();

        // then
        $this->assertEquals(5, $count);
    }

    /**
     * @test
     */
    public function shouldGet_First()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals($first, '');
    }

    /**
     * @test
     */
    public function shouldGet_First_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();

        // when
        $first = $matchPattern->first();

        // then
        $this->assertEquals('a', $first);
    }

    /**
     * @test
     */
    public function shouldNotGet_First_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

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
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $findFirst = $matchPattern->findFirst($callback);

        // then
        $this->assertEquals('value: ', $findFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldGet_findFirst_notFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // when
        $findFirst = $matchPattern->findFirst($callback);

        // then
        $this->assertEquals('value: a', $findFirst->orThrow());
        $this->assertInstanceOf(MatchedOptional::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldNotGet_findFirst_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();
        $callback = function (Match $match) {
            return 'value: ' . $match->text();
        };

        // then
        $findFirst = $matchPattern->findFirst($callback);

        // then
        $this->assertInstanceOf(NotMatchedOptional::class, $findFirst);
    }

    /**
     * @test
     */
    public function shouldMatch_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern_all();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldMatch_some()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertTrue($matches);
    }

    /**
     * @test
     */
    public function shouldNotMatch_matchedButFiltered()
    {
        // given
        $matchPattern = $this->standardMatchPattern_filtered();

        // when
        $matches = $matchPattern->test();

        // then
        $this->assertFalse($matches);
    }

    /**
     * @test
     */
    public function shouldFlatMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern_notFirst();
        $callback = function (Match $match) {
            return str_split(str_repeat($match, 2));
        };

        // when
        $flatMap = $matchPattern->flatMap($callback);

        // then
        $this->assertEquals(['a', 'a', 'b', 'b', '', 'c', 'c'], $flatMap);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_all()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $offsets = $matchPattern->offsets()->all();

        // then
        $this->assertEquals([1, 4, 12, 15], $offsets);
    }

    /**
     * @test
     */
    public function shouldGet_Offsets_first()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $offset = $matchPattern->offsets()->first();

        // then
        $this->assertEquals(1, $offset);
    }

    /**
     * @test
     */
    public function shouldMap()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $mapper = function (Match $match) {
            return lcfirst($match) . ucfirst($match);
        };

        // when
        $mapped = $matchPattern->map($mapper);

        // then
        $this->assertEquals(['', 'aA', '', 'cC'], $mapped);
    }

    /**
     * @test
     */
    public function shouldFindFirst()
    {
        // given
        $matchPattern = $this->standardMatchPattern();
        $callback = function (Match $match) {
            return 'for first: ' . $match->text();
        };

        // when
        $first = $matchPattern->findFirst($callback)->orReturn('');

        // then
        $this->assertEquals('for first: ', $first);
    }

    /**
     * @test
     */
    public function shouldChain_filter()
    {
        // given
        $pattern = '/\w+/';
        $subject = '...you forgot one very important thing mate.';
        $predicate = function (Match $match) {
            return $match->text() != 'forgot';
        };
        $pattern = new FilteredMatchPattern(new FilteredBaseDecorator(new ApiBase(InternalPattern::pcre($pattern), $subject, new UserData()), new Predicate($predicate)));

        // when
        $filtered = $pattern
            ->filter(function (Match $match) {
                return $match->text() !== 'very';
            })
            ->filter(function (Match $match) {
                return $match->text() !== 'mate';
            })
            ->all();

        // then
        $this->assertEquals(['you', 'one', 'important', 'thing'], $filtered);
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
        $this->assertEquals(['', 'a', '', 'c'], $matches);
    }

    /**
     * @test
     */
    public function shouldGet_iterator()
    {
        // given
        $matchPattern = $this->standardMatchPattern();

        // when
        $iterator = $matchPattern->getIterator();

        // then
        $array = [];
        foreach ($iterator as $match) {
            $array[] = $match->text();
        }
        $this->assertEquals(['', 'a', '', 'c'], $array);
    }

    private function standardMatchPattern(): FilteredMatchPattern
    {
        return $this->matchPattern(function (Match $match) {
            return $match->text() !== 'b';
        });
    }

    private function standardMatchPattern_all(): FilteredMatchPattern
    {
        return $this->matchPattern(Functions::constant(true));
    }

    private function standardMatchPattern_notFirst(): FilteredMatchPattern
    {
        return $this->matchPattern(function (Match $match) {
            return $match->index() > 0;
        });
    }

    private function standardMatchPattern_filtered(): FilteredMatchPattern
    {
        return $this->matchPattern(Functions::constant(false));
    }

    private function matchPattern(callable $predicate): FilteredMatchPattern
    {
        return new FilteredMatchPattern(
            new FilteredBaseDecorator(
                new ApiBase(
                    InternalPattern::standard($this->pattern()),
                    $this->subject(),
                    new UserData()
                ),
                new Predicate($predicate)
            )
        );
    }

    private function pattern(): string
    {
        return '(?<=\()[a-z]?(?=\))';
    }

    private function subject(): string
    {
        return '() (a) (b) () (c)';
    }
}
